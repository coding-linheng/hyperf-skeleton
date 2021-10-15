<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Repositories\V1\SucaiRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WaterDoRepository;
use App\Repositories\V1\WenkuRepository;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * SucaiService.
 *
 * @property User $userModel
 */
class SucaiService extends BaseService
{
    #[Inject]
    protected SucaiRepository $sucaiRepository;

    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected WenkuRepository $wenkuRepository;

    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /**
     * 模糊搜索素材数据，包含标题和关键字及其他筛选.
     *
     * @param $query
     *
     * @param $whereParam
     * @param $where
     * @param $order
     *
     * @return mixed
     */
    public function searchImgList($query, $whereParam, $where, $order)
    {
        return $this->sucaiRepository->searchImgList($query, $whereParam, $where, $order);
    }

    /**
     * 收藏素材图片.
     * 请求参数 id 收藏素材图片的id.
     *
     * @param $type
     *
     * @param $remark
     *
     * @return null|int|mixed
     */
    public function collectSucaiImg(int $id, $type, $remark): int|null
    {
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgInfo(['id' => $id], ['id', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }

        if ($sucaiInfo['uid'] == user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的作品！');
        }
        //取消采集
        if ($type == 2) {
            return $this->sucaiRepository->deleteCollectSucaiImg($sucaiInfo, user()['id']);
        }
        //采集
        return $this->sucaiRepository->collectSucaiImg($sucaiInfo, user()['id'], $remark);
    }

    /**
     * 素材详情页.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function getDetail(int $id): array|null
    {
        $uid = user()['id'];
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.del', 'img.status', 'img.img', 'uid', 'suffix', 'size', 'height', 'name',  'title', 'guanjianci',  'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        $sucaiInfo = $sucaiInfo->toArray();
        //已删除
        if ($sucaiInfo['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '素材已删除！');
        }
        //未正常通过
        if ($sucaiInfo['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '素材暂时不能查看！');
        }
        //获取详情图片
        $info['pictures'] = $this->sucaiRepository->getImgsUrl($sucaiInfo['img']);

        //查询他的作品个数
        $sucaiInfo['total_num'] = $this->sucaiRepository->totalImgCount(['uid' => $sucaiInfo['uid'], 'del' => 0, 'status' => 3]);

        //是否关注
        $guanZhuUser =  $this->userRepository->isGuanzhuUser($uid, $sucaiInfo['uid']);

        if (empty($guanZhuUser)) {
            $sucaiInfo['guan_user'] = 1;
        } else {
            $sucaiInfo['guan_user'] = 2;
        }

        //是否收藏
        $shoucang = $this->sucaiRepository->isShouCangImg($uid, $sucaiInfo['id']);

        if (empty($shoucang)) {
            $sucaiInfo['shoucang'] = 1;
        } else {
            $sucaiInfo['shoucang'] = 2;
        }
        //类型
        $sucaiInfo['fenlei'] = $this->sucaiRepository->getFenLei($id);
        $sucaiInfo['format'] = $this->sucaiRepository->getImgFormat($id);
        //浏览量
        $this->sucaiRepository->IncImgCount($id);
        //广告位
        $sucaiInfo['advertisement'] = $this->sucaiRepository->getSuCaiAdvertisement();
        //关键词
        $linglebel = array_unique(explode(' ', $sucaiInfo['guanjianci']));

        foreach ($linglebel as $k => $v) {
            if (trim($v) == '') {
                unset($linglebel[$k]);
            }
        }
        $sucaiInfo['key_words'] = $linglebel;
        $sucaiInfo['userdata']  = $this->userRepository->getUserData($sucaiInfo['uid'], ['id', 'uid', 'name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1']);
        return $sucaiInfo;
    }

    /**
     * 相关推荐.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function recommendList(int $id): array|null
    {
        //本类素材
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.del', 'img.status', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        //如果有筛选，则处理
        $queryString = $sucaiInfo['title'] . ' ' . $sucaiInfo['guanjianci'];
        $queryParam  = ['title' => ['or', "{$queryString}"], 'guanjianci' => ['or', "{$queryString}"]];
        $where       = [];
        return $this->searchImgList($queryString, $queryParam, $where, '');
    }

    /**
     * 素材详情页--作者其他.
     */
    public function getListByAuthor(int $id): array|null
    {
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        return $this->sucaiRepository->getListPageRand($sucaiInfo['uid']);
    }

    /**
     * 获取素材的下载地址.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function getDownUrl(int $id): array|null
    {
        $uid = user()['id'];
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgInfo(['id' => $id], ['id', 'del', 'status', 'img', 'path', 'path', 'uid', 'suffix', 'size', 'height', 'name',  'week', 'title', 'guanjianci',  'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        $sucaiInfo = $sucaiInfo->toArray();
        //已删除
        if ($sucaiInfo['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '素材已删除！');
        }
        //未正常通过
        if ($sucaiInfo['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '素材暂时不能下载！');
        }

        //如果是自己下载自己的则直接返回
        if ($sucaiInfo['uid'] == $uid && $uid != 0) {
            $downLoadUrl = get_img_path_private($sucaiInfo['path']);
            return ['suffix' => $sucaiInfo['suffix'], 'title' => $sucaiInfo['title'], 'downLoadUrl' => $downLoadUrl];
        }

        //如果是下载别人的则需要处理下载数量，扣除共享分等
        if ($sucaiInfo['leixing'] == 1 && $sucaiInfo['price'] != 0) {
            //下载共享素材
            $ret = $this->downShareSucai($sucaiInfo, $uid);
        } elseif ($sucaiInfo['leixing'] == 2) {
            //下载原创素材
            $ret = $this->downDcSucai($sucaiInfo, $uid);
        } else {
            //免费下载
            $ret = $this->downFreeSucai($sucaiInfo, $uid);
        }

        if (!$ret) {
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //统计最近7天下载
        $this->sucaiRepository->recodeWeekDownNum($sucaiInfo);

        //缓存七天，七天之内下载过的可以免费下载，新版忽略
        //cache($this->uid.$id.'sucai',true,604800);
        $downLoadUrl = get_img_path_private($sucaiInfo['path']);
        return ['suffix' => $sucaiInfo['suffix'], 'title' => $sucaiInfo['title'], 'downLoadUrl' => $downLoadUrl];
    }

    /**
     * 下载共享素材.
     *
     * @param $sucaiInfo
     * @param mixed $uid
     *
     * @return bool
     */
    private function downShareSucai($sucaiInfo, $uid)
    {
        $time      = strtotime(date('Y-m-d'));
        $sucaidown = $this->sucaiRepository->getSuCaiDown(['uid' => $this->uid, 'time' => $time]);

        if (!empty($sucaidown)) {
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr = explode(',', $sucaidown['ids']);

            if (in_array($sucaiInfo['id'], $arr)) {
                return true;
            }
        }

        //获取配置
        $scorelist = $this->getscore($sucaiInfo['price']);
        $score     = $scorelist['score'];
        $postscore = $scorelist['postscore'];

        //判断是不是素材vip时间
        $uservip = $this->userRepository->getUserVip(['uid' => $this->uid, 'type' => 3]);
        Db::beginTransaction();

        if (!empty($uservip) && $uservip->time >= time()) {
            $uservip = $uservip->toArray();
            //本人是素材vip直接下载
            $time = strtotime(date('Y-m-d'));
            //当天第一次下载可以下载免费素材，后续没权限的则没法下载
            $sucaidown = $this->sucaiRepository->getSuCaiDown(['uid' => $uid, 'time' => $time]);
            //你下载了素材
            $addWaterDownData = [
                'wid'   => $sucaiInfo['id'],
                'bid'   => $sucaiInfo['uid'],
                'uid'   => $uid,
                'score' => 0,
                'dc'    => 0,
                'type'  => 2,
                'vip'   => 1, //vip下载
                'time'  => time(),
            ];

            if (empty($sucaidown)) {
                if (!$this->sucaiRepository->addSuCaiDown(['ids' => $sucaiInfo['id'], 'uid' => $uid, 'time' => $time])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                //你下载了素材
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }

                //增加共享分
                if (!$this->waterDoRepository->addUserScore($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                Db::commit();
                return true;
            }
            $count = 0;

            if ($uservip['vip'] == 3) {
                $count = 5;
            } elseif ($uservip['vip'] == 4) {
                $count = 10;
            } elseif ($uservip['vip'] == 5) {
                $count = 20;
            } elseif ($uservip['vip'] == 6) {
                $count = 8;
            } elseif ($uservip['vip'] == 7) {
                $count = 10;
            }
            $arr = explode(',', $sucaidown['ids']);
            //当天下载过免费
            if (in_array($sucaiInfo['id'], $arr)) {
                Db::commit();
                return true;
            }

            if (count($arr) < $count) {
                $ids = $sucaidown['ids'] . ',' . $sucaiInfo['id'];

                if (!$this->sucaiRepository->updateSuCaiDown(['id' => $sucaidown['id']], ['ids' => $ids])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                //下载日志
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                //增加共享分
                if (!$this->waterDoRepository->addUserScore($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
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

        if ($userinfo->score < $score) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您的共享分不足！');
        }
        //扣除积分
        if (!$this->userRepository->decScore($uid, $score)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //增加扣除积分流水
        if (!$this->waterDoRepository->addWaterScore($uid, $score, $sucaiInfo['id'], 1, 4, $sucaiInfo['uid'], $sucaiInfo['title'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //增加下载流水
        if (!$this->waterDoRepository->addWaterDown($sucaiInfo['id'], $sucaiInfo['uid'], $uid, $score)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //给主人增加积分
        if (!$this->waterDoRepository->addUserScore($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //增加下载量
        if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
        }
        Db::commit();
        return true;
    }

    /**
     * 下载原创素材.
     *
     * @param $sucaiInfo
     * @param mixed $uid
     *
     * @return bool
     */
    private function downDcSucai($sucaiInfo, $uid)
    {
        $time      = strtotime(date('Y-m-d'));
        $sucaidown = $this->sucaiRepository->getSuCaiDown(['uid' => $this->uid, 'time' => $time]);

        if (!empty($sucaidown)) {
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr = explode(',', $sucaidown['ids']);

            if (in_array($sucaiInfo['id'], $arr)) {
                return true;
            }
        }

        //花的原创币
        //判断用户是不是在时间之内
        $uservip = $this->userRepository->getUserVip(['uid' => $this->uid, 'type' => 4]);
        Db::beginTransaction();

        if (!empty($uservip) && $uservip->time >= time()) {
            $uservip = $uservip->toArray();
            //本人是素材vip直接下载
            $time = strtotime(date('Y-m-d'));
            //当天第一次下载可以下载免费素材，后续没权限的则没法下载
            $sucaidown = $this->sucaiRepository->getSuCaiDown(['uid' => $uid, 'time' => $time]);
            //你下载了素材
            $addWaterDownData = [
                'wid'   => $sucaiInfo['id'],
                'bid'   => $sucaiInfo['uid'],
                'uid'   => $uid,
                'score' => 0,
                'dc'    => 0,
                'type'  => 2,
                'vip'   => 1, //vip下载
                'time'  => time(),
            ];

            if (empty($sucaidown)) {
                if (!$this->sucaiRepository->addSuCaiDown(['ids' => $sucaiInfo['id'], 'uid' => $uid, 'time' => $time])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                //你下载了素材
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                //给用户增加原创币流水
                $postscore = 0.10; //给钱

                if (!$this->waterDoRepository->addUserDc($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'], 1)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                Db::commit();
                return true;
            }

            if ($uservip['vip'] == 6) {
                $count = 8;
            } elseif ($uservip['vip'] == 7) {
                $count = 10;
            }
            $arr = explode(',', $sucaidown['ids']);

            if (in_array($sucaiInfo['id'], $arr)) {
                Db::commit();
                return true;
            }

            if (count($arr) < $count) {
                $ids = $sucaidown['ids'] . ',' . $sucaiInfo['id'];

                if (!$this->sucaiRepository->updateSuCaiDown(['id' => $sucaidown['id']], ['ids' => $ids])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
                }
                //下载日志
                if (!$this->waterDoRepository->addWaterDownData($addWaterDownData)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
                }

                //给用户增加原创币流水
                $postscore = 0.10; //给钱
                if (!$this->waterDoRepository->addUserDc($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'], 1)) {
                    Db::rollBack();
                    throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
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

        if ($userinfo->dc < $sucaiInfo['price']) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您的原创币不够！');
        }

        if (!$this->userRepository->decDc($uid, $sucaiInfo['price'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }

        //增加下载流水
        if (!$this->waterDoRepository->addWaterDown($sucaiInfo['id'], $sucaiInfo['uid'], $uid, 0, $sucaiInfo['price'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }

        $add           = [];
        $add['uid']    = $uid;
        $add['score']  = $sucaiInfo['price'];
        $add['type']   = 4;
        $add['status'] = 1; //素材下载扣除
        $add['wid']    = $sucaiInfo['id'];
        $add['bid']    = $sucaiInfo['id'];
        $add['name']   = $sucaiInfo['title'];
        $add['time']   = time();

        if (!$this->waterDoRepository->addWaterDc($add)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        //给用户增加原创币流水
      $postscore = round($sucaiInfo['price'] * 0.9, 2); //给的原创币

      if (!$this->waterDoRepository->addUserDc($sucaiInfo['uid'], $postscore, $sucaiInfo['id'], 1, 3, $uid, $sucaiInfo['title'])) {
          Db::rollBack();
          throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
      }

        if (!$this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
        }

        Db::commit();
        return true;
    }

    /**
     * 下载免费素材.
     * @param mixed $sucaiInfo
     * @param mixed $uid
     * @return bool
     */
    private function downFreeSucai($sucaiInfo, $uid)
    {
        //判断是否有权限
        $time     = strtotime(date('Y-m-d'));
        $quanxian = $this->userRepository->jurisdiction($uid);
        Db::beginTransaction();
        //当天第一次下载可以下载免费素材，后续没权限的则没法下载
        $dayinfo = $this->sucaiRepository->getDayDownSuCai(['uid' => $uid, 'time' => $time]);

        if (empty($dayinfo)) {
            $add = [
                'uid' => $uid, 'time' => $time, 'num' => 1,
            ];

            if (!$this->sucaiRepository->addDayDownSuCai($add)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
            }

            if (!$this->waterDoRepository->addWaterDown($sucaiInfo['id'], $sucaiInfo['uid'], $uid, 0)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
            }
        }
        $dayinfo = $dayinfo->toArray();

        if ($quanxian === false || $quanxian['sucai'] == 0) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '您暂无权限下载！');
        }

        if ($quanxian['sucai'] != 0) {
            //1代表vip1  5个；2代表vip2 8个；3代表vip3 10个；4代表vip4 20个
            if (($quanxian['sucai'] == 1 && $dayinfo['num'] >= 5) || ($quanxian['sucai'] == 2 && $dayinfo['num'] >= 8) || ($quanxian['sucai'] == 3 && $dayinfo['num'] >= 10) || ($quanxian['sucai'] == 4 && $dayinfo['num'] >= 20)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '您每天只能下载' . $quanxian['sucai'] . '个素材！');
            }

            if (!$this->sucaiRepository->incDayDownSuCai(['uid' => $this->uid, 'time' => $time])) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载');
            }
            //增加下载流水
            if ($this->waterDoRepository->addWaterDown($sucaiInfo['id'], $sucaiInfo['uid'], $uid, 0)) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
            }
        }

        if ($this->sucaiRepository->incImgDownNum($sucaiInfo['id'])) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '暂时无法下载！');
        }
        Db::commit();
        return true;
    }
}
