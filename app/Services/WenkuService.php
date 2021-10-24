<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WaterDoRepository;
use App\Repositories\V1\WenkuRepository;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * WenkuService.
 *
 * @property User $userModel
 */
class WenkuService extends BaseService
{
    #[Inject]
    protected WenkuRepository $wenkuRepository;

    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /**
     * 模糊搜索文库数据，包含标题和关键字及其他筛选列表.
     * query 搜素关键字，热门搜索，不填为全部
     * order 排序字段： 最新 dtime，热门下载 downnum，推荐 tui
     * lid 不传该字段或者传0则表示默认全部，1共享，2原创, mulu_id：分类id.
     */
    public function getList(array $query): array|null
    {
        return $this->wenkuRepository->getSearchWenkuList($query);
    }

    /**
     * 详情页.
     * @param: id 文库的id
     *
     * @return null|array|mixed
     */
    public function getDetail(int $id): array|null
    {
        $uid  = user()['id'];
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }

        $info = json_decode(json_encode($info), true);
        unset($info['path']);

        //已删除
        if ($info['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '文库已删除！');
        }
        //未正常通过
        if ($info['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '文库暂时不能查看！');
        }
        $info['pdf'] = urlencode($info['pdf']);
        //获取详情图片
        $info['pictures'] = $this->wenkuRepository->getImgsUrl($info['img']);

        //是否关注
        $guanZhuUser =  $this->userRepository->isGuanzhuUser($uid, $info['uid']);

        if (empty($guanZhuUser)) {
            $info['guan_user'] = 1;
        } else {
            $info['guan_user'] = 2;
        }

        //是否收藏
        $shoucang = $this->wenkuRepository->isShouCang($uid, $info['id']);

        if (empty($shoucang)) {
            $info['shoucang'] = 1;
        } else {
            $info['shoucang'] = 2;
        }

        //增加浏览量
        $this->wenkuRepository->incLookNum($id);
        //广告位
        $info['advertisement'] = $this->wenkuRepository->getAdvertisement(7);
        //用户数据
        $info['userdata']  = $this->userRepository->getUserData($info['uid'], ['id', 'uid', 'name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1']);
        return $info;
    }

    /**
     * 文库详情页--相关推荐.
     * @param: id 文库的id
     *
     * @return null|array|mixed
     */
    public function recommendList(mixed $query): array|null
    {
        //猜你喜欢
        $query['rand'] = 1;
        return $this->wenkuRepository->getSearchWenkuList($query);
    }

    /**
     * 文库详情页--作者其他.
     * @param: id 文库的id
     * @param mixed $query
     */
    public function getListByAuthor(int $id, $query): array|null
    {
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $query['uid'] = $info->uid;
        return $this->wenkuRepository->getSearchWenkuList($query);
    }

    /**
     * 文库详情页--收藏文库
     * 请求参数 id 收藏文库的id.
     *
     * @param $type
     */
    public function collectDocument(int $id, $type): int|null
    {
        //判断是否存在
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $info = json_decode(json_encode($info), true);

        if ($info['uid'] == user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的文库！');
        }
        //取消采集
        if ($type == 2) {
            return $this->wenkuRepository->deleteCollectDocument($info, user()['id']);
        }
        //采集
        return $this->wenkuRepository->collectDocument($info, user()['id']);
    }

    /**
     * 获取文库的下载地址.
     * @param: id 文库的id
     *
     * @return null|array|mixed
     */
    public function getDownUrl(int $id): array|null
    {
        $uid = user()['id'];
        //判断图片是否存在
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $info = json_decode(json_encode($info), true);
        //已删除
        if ($info['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '文库已删除！');
        }
        //未正常通过
        if ($info['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '文库暂时不能下载！');
        }
        $info        = json_decode(json_encode($info), true);
        $downLoadUrl = get_img_path_private($info['path']);

        //如果是自己下载自己的则直接返回
        if ($info['uid'] == $uid && $uid != 0) {
            $downLoadUrl = get_img_path_private($info['path']);
            return ['suffix' => $info['suffix'], 'title' => $info['title'], 'downLoadUrl' => $downLoadUrl];
        }

        //缓存七天，七天之内下载过的可以免费下载
        $sevenDayDown = getCache($uid . $id . 'wenku');

        if (!empty($sevenDayDown)) {
            $downLoadUrl = get_img_path_private($info['path']);
            return ['suffix' => $info['suffix'], 'title' => $info['title'], 'downLoadUrl' => $downLoadUrl];
        }

        //如果是下载别人的则需要处理下载数量，扣除共享分等
        if ($info['leixing'] == 1 && $info['price'] != 0) {
            //下载共享文库
            $ret = $this->downShareWenKu($info, $uid);
        } elseif ($info['leixing'] == 2) {
            //下载原创文库
            $ret = $this->downDcWenKu($info, $uid);
        } else {
            //免费下载
            $ret = $this->downFreeWenKu($info, $uid);
        }

        if (!$ret) {
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }
        //统计最近7天下载
        $this->wenkuRepository->recodeWeekDownNum($info);

        //缓存七天，七天之内下载过的可以免费下载
        setCache($uid . $id . 'wenku', 'true', 604800);
        $downLoadUrl = get_img_path_private($info['path']);
        return ['suffix' => $info['suffix'], 'title' => $info['title'], 'downLoadUrl' => $downLoadUrl];
    }

    /**
     * 下载共享.
     *
     * @param $wenKuInfo
     * @param mixed $uid
     *
     * @return bool
     */
    private function downShareWenKu($wenKuInfo, $uid)
    {
        $time      = strtotime(date('Y-m-d'));
        //当天第一次下载可以下载免费文库，后续没权限的则没法下载
        $wenKudown = $this->wenkuRepository->getWenKuDown(['uid' => $uid, 'time' => $time]);

        if (!empty($wenKudown)) {
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr = explode(',', $wenKudown['ids']);

            if (in_array($wenKuInfo['id'], $arr)) {
                return true;
            }
        }

        //判断是不是文库vip时间
        $uservip = $this->userRepository->getUserVip(['uid' => $uid, 'type' => 5]);
        Db::beginTransaction();

        if (!empty($uservip) && $uservip->time >= time()) {
            $uservip = $uservip->toArray();
            //本人是文库vip直接下载
            //你下载了文库
            $addWaterDownData = [
                'wid'   => $wenKuInfo['id'],
                'bid'   => $wenKuInfo['uid'],
                'uid'   => $uid,
                'score' => 0,
                'dc'    => 0,
                'type'  => 1,
                'vip'   => 1, //vip下载
                'time'  => time(),
            ];

            if (empty($wenKudown)) {
                if (!$this->wenkuRepository->addWenKuDown(['ids' => $wenKuInfo['id'], 'uid' => $uid, 'time' => $time])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //你下载了文库
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }

                //文库旧版本是没有增加共享分的
//                if (!$this->waterDoRepository->addUserScore($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 1, 3, $uid, $wenKuInfo['title'])) {
//                    Db::rollBack();
//                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
//                }
                Db::commit();
                return true;
            }
            $count = 0;

            if ($uservip['vip'] == 1) {
                $count = 5;
            } elseif ($uservip['vip'] == 2) {
                $count = 8;
            } elseif ($uservip['vip'] == 3) {
                $count = 10;
            }
            $arr = explode(',', $wenKudown['ids']);
            //当天下载过免费
            if (in_array($wenKuInfo['id'], $arr)) {
                Db::commit();
                return true;
            }

            if (count($arr) < $count) {
                $ids = $wenKudown['ids'] . ',' . $wenKuInfo['id'];

                if (!$this->wenkuRepository->updateWenkuDown(['id' => $wenKudown['id']], ['ids' => $ids])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //下载日志
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }

                //增加共享分
//                if (!$this->waterDoRepository->addUserScore($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 1, 3, $uid, $wenKuInfo['title'])) {
//                    Db::rollBack();
//                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
//                }

                Db::commit();
                return true;
            }
        }

        $userinfo = $this->userRepository->getUser($uid);

        if (empty($userinfo)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '请重新登录后再重试！');
        }

        switch ($wenKuInfo['price']) {
          case 1:
            $score     = 20; //10
            $postscore = 10;
            break;
          case 2:
            $score     = 40; //20
            $postscore = 20;
            break;
          case 3:
            $score     = 60; //30
            $postscore = 30;
            break;
          case 4:
            $score     = 100; //40
            $postscore = 40;
            break;
          case 5:
            $score     = 200; //80
            $postscore = 80;
            break;
        }

        if ($userinfo->score < $score) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您的共享分不足！');
        }
        //扣除积分
        if (!$this->userRepository->decScore($uid, $score)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }
        //增加扣除积分流水
        if (!$this->waterDoRepository->addWaterScore($uid, $score, $wenKuInfo['id'], 2, 4, $wenKuInfo['uid'], $wenKuInfo['title'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }
        //增加下载流水
        if (!$this->waterDoRepository->addWaterDown($wenKuInfo['id'], $wenKuInfo['uid'], $uid, $score)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }
        //给主人增加积分
        if (!$this->waterDoRepository->addUserScore($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 2, 3, $uid, $wenKuInfo['title'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }
        //增加下载量
        if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        if (empty($wenKudown)) {
            if (!$this->wenkuRepository->addWenKuDown(['ids' => $wenKuInfo['id'], 'uid' => $uid, 'time' => $time])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
            }
        } else {
            $ids = $wenKudown['ids'] . ',' . $wenKuInfo['id'];

            if (!$this->wenkuRepository->updateWenkuDown(['id' => $wenKudown['id']], ['ids' => $ids])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
            }
        }
        Db::commit();
        return true;
    }

    /**
     * 下载原创.
     *
     * @param $wenKuInfo
     * @param mixed $uid
     *
     * @return bool
     */
    private function downDcWenKu($wenKuInfo, $uid)
    {
        $time      = strtotime(date('Y-m-d'));
        //当天第一次下载可以下载免费文库，后续没权限的则没法下载
        $wenKudown = $this->wenkuRepository->getWenKuDownDc(['uid' => $uid, 'time' => $time]);

        if (!empty($wenKudown)) {
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr = explode(',', $wenKudown['ids']);

            if (in_array($wenKuInfo['id'], $arr)) {
                return true;
            }
        }

        //花的原创币
        //判断用户是不是在时间之内
        $uservip = $this->userRepository->getUserVip(['uid' => $uid, 'type' => 6]);
        Db::beginTransaction();

        if (!empty($uservip) && $uservip->time >= time()) {
            $uservip = $uservip->toArray();
            //本人是文库vip直接下载
            $time = strtotime(date('Y-m-d'));

            //你下载了文库
            $addWaterDownData = [
                'wid'   => $wenKuInfo['id'],
                'bid'   => $wenKuInfo['uid'],
                'uid'   => $uid,
                'score' => 0,
                'dc'    => 0,
                'type'  => 1,
                'vip'   => 1, //vip下载
                'time'  => time(),
            ];

            if (empty($wenKudown)) {
                if (!$this->wenkuRepository->addWenKuDownDc(['ids' => $wenKuInfo['id'], 'uid' => $uid, 'time' => $time])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //你下载了文库
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //给用户增加原创币流水
                $postscore = 0.10; //给钱

                if (!$this->waterDoRepository->addUserDc($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 1, 3, $uid, $wenKuInfo['title'], 1)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                Db::commit();
                return true;
            }

            if ($uservip['vip'] == 2) {
                $count = 8;
            } elseif ($uservip['vip'] == 3) {
                $count = 10;
            }
            $arr = explode(',', $wenKudown['ids']);

            if (in_array($wenKuInfo['id'], $arr)) {
                if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                Db::commit();
                return true;
            }

            if (count($arr) < $count) {
                $ids = $wenKudown['ids'] . ',' . $wenKuInfo['id'];

                if (!$this->wenkuRepository->updateWenkuDownDc(['id' => $wenKudown['id']], ['ids' => $ids])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //下载日志
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }
                //给用户增加原创币流水
                $postscore = 0.10; //给钱

                if (!$this->waterDoRepository->addUserDc($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 1, 3, $uid, $wenKuInfo['title'], 1)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
                }

                Db::commit();
                return true;
            }
        }

        $userinfo = $this->userRepository->getUser($uid);

        if (empty($userinfo)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '请重新登录后再重试！');
        }

        if ($userinfo->dc < $wenKuInfo['price']) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您的原创币不够！');
        }

        if (!$this->userRepository->decDc($uid, $wenKuInfo['price'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        //增加下载流水
        if (!$this->waterDoRepository->addWaterDown($wenKuInfo['id'], $wenKuInfo['uid'], $uid, 0, $wenKuInfo['price'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        $add           = [];
        $add['uid']    = $uid;
        $add['score']  = $wenKuInfo['price'];
        $add['type']   = 4;
        $add['status'] = 2; //文库原创币
        $add['wid']    = $wenKuInfo['id'];
        $add['bid']    = $wenKuInfo['id'];
        $add['name']   = $wenKuInfo['title'];
        $add['time']   = time();

        if (!$this->waterDoRepository->addWaterDc($add)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        //给作者70%的原创币 //给主人增加积分
        //给用户增加原创币流水
        $postscore = round($wenKuInfo['price'] * 0.9, 2); //给的原创币

        if (!$this->waterDoRepository->addUserDc($wenKuInfo['uid'], $postscore, $wenKuInfo['id'], 2, 3, $uid, $wenKuInfo['title'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
        }

        if (empty($wenKudown)) {
            if (!$this->wenkuRepository->addWenKuDownDc([
                'ids' => $wenKuInfo['id'],
                'uid' => $uid,
                'time' => $time,
            ])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
            }
        } else {
            $ids = $wenKudown['ids'] . ',' . $wenKuInfo['id'];

            if (!$this->wenkuRepository->updateWenkuDownDc(['id' => $wenKudown['id']], ['ids' => $ids])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '该文库暂时无法下载！');
            }
        }
        Db::commit();
        return true;
    }

    /**
     * 下载免费.
     * @param mixed $wenKuInfo
     * @param mixed $uid
     * @return bool
     */
    private function downFreeWenKu($wenKuInfo, $uid)
    {
        //判断是否有权限
        $time     = strtotime(date('Y-m-d'));
        $quanxian = $this->userRepository->jurisdiction($uid);
        Db::beginTransaction();

        if ($quanxian === false || $quanxian['wenku'] == 0) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您暂无权限下载！');
        }

        //当天第一次下载可以下载免费文库，后续没权限的则没法下载
        $dayinfo = $this->wenkuRepository->getDayDown(['uid' => $uid, 'time' => $time]);

        if (empty($dayinfo)) {
            $add = [
                'uid' => $uid, 'time' => $time, 'num' => 1,
            ];

            if (!$this->wenkuRepository->addDayDown($add)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
            }
        } else {
            if (!$this->wenkuRepository->incDayDown(['uid' => $uid, 'time' => $time])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载');
            }
            $dayinfo = $dayinfo->toArray();
        }

        if ($quanxian['wenku'] != 0 && !empty($dayinfo)) {
            //1代表vip1  10个；2代表vip2 20个；3代表vip3 40个；4代表vip4 100个
            if (($quanxian['wenku'] == 1 && $dayinfo['num'] >= 10) || ($quanxian['wenku'] == 2 && $dayinfo['num'] >= 20) || ($quanxian['wenku'] == 3 && $dayinfo['num'] >= 40) || ($quanxian['wenku'] == 4 && $dayinfo['num'] >= 100)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '您每天只能下载' . $quanxian['wenku'] . '个文库！');
            }
        }

        //增加下载流水
        if (!$this->waterDoRepository->addWaterDown($wenKuInfo['id'], $wenKuInfo['uid'], $uid, 0)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
        }

        if (!$this->wenkuRepository->incDownNum($wenKuInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
        }
        Db::commit();
        return true;
    }
}
