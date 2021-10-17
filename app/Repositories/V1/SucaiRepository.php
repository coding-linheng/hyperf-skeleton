<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\Daydownsucai;
use App\Model\Fenlei;
use App\Model\Fenleirelation;
use App\Model\Geshi;
use App\Model\Geshirelation;
use App\Model\Img;
use App\Model\Mulu;
use App\Model\Shouimg;
use App\Model\Sucaidown;
use App\Model\Sucaidowndc;
use App\Model\Sucaiguanggao;
use App\Model\Userdata;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * 素材.
 */
class SucaiRepository extends BaseRepository
{
    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /**
     * 自定义随机分页列表.
     * @param mixed $uid
     */
    public function getListPageRand($uid): array
    {
        $sql         = 'SELECT * FROM dczg_img as i where i.del<=1 and i.status=3 and i.uid=' . $uid;
        $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_img)-(SELECT MIN(id) FROM dczg_img)) + (SELECT MIN(id) FROM dczg_img))) limit 0,40';
        $randListArr = Db::select($sql, []);
        //处理数据
        if (!empty($randListArr)) {
            //找到目录列表
            $muluArr = $this->getMulu();

            foreach ($randListArr as $key => &$val) {
                $tmp['id']          = $val->id ?? 0;
                //$tmp['path']        = get_img_path($val->path, ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['img']           = $this->getPictureJson($val->img);
                $tmp['title']         = $val->title    ?? '';
                $tmp['shoucang']      = $val->shoucang ?? 0;
                $tmp['price']         = $val->price    ?? 0;
                $tmp['leixing']       = $val->leixing  ?? 0;
                $tmp['downnum']       = $val->downnum  ?? 0;
                $tmp['dtime']         = $val->dtime    ?? 0;
                $tmp['mulu']          = isset($val->mulu_id) && isset($muluArr[$val->mulu_id]) ? $muluArr[$val->mulu_id] : '';
                $randListArr[$key]    = $tmp;
                $tmp                  = [];
            }
        }
        return $randListArr;
    }

    /**
     * 模糊搜索素材数据，包含标题和关键字及其他筛选.
     *
     * @param $query
     *
     * @param $whereParam
     * @param $where
     * @param $order
     * @param mixed $wheres
     *
     * @return mixed
     */
    public function searchImgList($query, $whereParam, $wheres, $order)
    {
        $orm = Img::search($query, es_callback($whereParam));

        if (!empty($order)) {
            $orm = $orm->orderBy($order, 'desc');
        }

        if (!empty($wheres)) {
            foreach ($wheres as $field => $where) {
                $orm = $orm->where($field, $where);
            }
        }
        $list = $orm->paginateRaw(200)->toArray();
        $list = format_es_page_raw_data($list);
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
            //找到目录列表
            $muluArr = $this->getMulu();

            foreach ($list['data'] as $key => &$val) {
                if (!isset($val['id']) || empty($val['title'])) {
                    unset($list['data'][$key]);
                    continue;
                }
                $tmp['id']            = $val['id'] ?? 0;
                //$tmp['path']          = get_img_path($val['path'], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['img']           = $this->getPictureJson($val['img'], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']         = $val['title']     ?? '';
                $tmp['shoucang']      = $val['shoucang']  ?? 0;
                $tmp['downnum']       = $val['downnum']   ?? 0;
                $tmp['dtime']         = $val['dtime']     ?? 0;
                $tmp['price']         = $val['price']     ?? 0;
                $tmp['leixing']       = $val['leixing']   ?? 0;
                $tmp['mulu']          = isset($val['mulu_id']) && isset($muluArr[$val['mulu_id']]) ? $muluArr[$val['mulu_id']] : '';
                $list['data'][$key]   = $tmp;
                $tmp                  = [];
            }
        }
        return $list;
    }

    public function getMulu()
    {
        $muluArr    = [];
        $muluArrRes = Mulu::query()->get()->toArray();

        if (!empty($muluArrRes)) {
            foreach ($muluArrRes as $k => $v) {
                $muluArr[$v['id']] = $v['name'];
            }
        }
        return $muluArr;
    }

    /**
     * 获取素材信息.
     */
    public function getSucaiImgInfo(array $where, array $column = ['*']): Model|Builder|null
    {
        return Img::query()->where($where)->select($column)->first();
    }

    /**
     * 获取素材信息包含作者昵称头像.
     */
    public function getSucaiImgDetailInfo(array $where, array $column = ['img.*', 'u.nickname', 'u.imghead']): Model|Builder|null
    {
        return Img::query()->leftJoin('user as u', 'u.id', '=', 'img.uid')->where($where)->select($column)->first();
    }

    /**
     * 获取共享素材下载信息.
     */
    public function getSuCaiDown(array $where, array $column = ['*']): Model|Builder|null
    {
        return Sucaidown::query()->where($where)->select($column)->first();
    }

    /**
     * 添加共享素材下载信息.
     *
     * @param array $where
     * @param array $column
     */
    public function addSuCaiDown(array $data): int
    {
        return Sucaidown::query()->insertGetId($data);
    }

    /**
     * 修改共享素材下载信息.
     *
     * @param array $column
     */
    public function updateSuCaiDown(array $where, array $data): int
    {
        return Sucaidown::query()->where($where)->update($data);
    }

    /**
     * 获取地产币素材下载信息.
     */
    public function getSuCaiDownDc(array $where, array $column = ['*']): Model|Builder|null
    {
        return Sucaidowndc::query()->where($where)->select($column)->first();
    }

    /**
     * 获取当天免费素材下载信息.
     */
    public function getDayDownSuCai(array $where, array $column = ['*']): Model|Builder|null
    {
        return Daydownsucai::query()->where($where)->select($column)->first();
    }

    /**
     * 增加当天免费素材下载信息.
     */
    public function addDayDownSuCai(array $data): int
    {
        return Daydownsucai::query()->insertGetId($data);
    }

    /**
     * 增加当天免费素材下载次数.
     *
     * @param int $num
     */
    public function incDayDownSuCai(array $where, $num = 1): int
    {
        return Daydownsucai::query()->where($where)->increment('num', $num);
    }

    /**
     * 增加在看次数.
     *
     * @param mixed $id
     * @param int $num
     */
    public function IncImgCount($id, $num = 1): int
    {
        return Img::query()->where('id', $id)->increment('looknum', $num);
    }

    /**
     * 增加下载次数.
     *
     * @param mixed $id
     * @param int $num
     */
    public function incImgDownNum($id, $num = 1): int
    {
        return Img::query()->where('id', $id)->increment('downnum', $num);
    }

    /**
     * 统计作品个数.
     */
    public function totalImgCount(array $where): int
    {
        return Img::query()->where($where)->count();
    }

    /**
     * 是否关收藏图片.
     * @param mixed $uid
     * @param mixed $targetId
     */
    public function isShouCangImg($uid, $targetId): Model|null
    {
        return Shouimg::query()->where(['uid' => $uid, 'iid' => $targetId])->first();
    }

    /**
     * 获取分类.
     * @param mixed $id
     */
    public function getFenLei($id): Model|null
    {
        $fenleiid = Fenleirelation::query()->where(['iid' => $id])->orderBy('mid', 'asc')->first();
        return Fenlei::query()->where(['id' => $fenleiid['mid']])->first();
    }

    /**
     * 获取格式.
     * @param mixed $id
     */
    public function getImgFormat($id): Model|null
    {
        $geshi = Geshirelation::query()->where(['iid' => $id])->first();
        return Geshi::query()->where(['id' => $geshi['mid']])->first();
    }

    /**
     * 获取目录.
     * @param mixed $id
     */
    public function getMuluInfo($id): Model|null
    {
        return Mulu::query()->where(['id' => $id])->first();
    }

    /**
     * 获取素材信息列表.
     */
    public function getMaterialList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $orm      = Img::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 获取素材统计
     */
    public function getMaterialStatistics(array $where): Collection|array
    {
        return Img::query()->where($where)->groupBy(['status'])->select(Db::raw('count(status) as count'), 'status')->get()->toArray();
    }

    /**
     * 收藏素材图片.
     *
     * @param $sucaiInfo
     * @param $uid
     *
     * @param $remark
     */
    public function collectSucaiImg($sucaiInfo, $uid, $remark): int|null
    {
        $shouLingInfo = Shouimg::where(['uid' => user()['id'], 'iid' => $sucaiInfo['id']])->first();

        if (!empty($shouLingInfo)) {
            return $sucaiInfo['shoucang'];
        }

        Db::beginTransaction();
        $add             = [];
        $add['uid']      = $uid;
        $add['iid']      = $sucaiInfo['id'];
        $add['img_url']  = $sucaiInfo['path'];
        $add['album_id'] = 0;
        $add['img_uid']  = $sucaiInfo['uid'];
        $add['c_time']   = time();
        $add['remark']   = $remark;

        if (!Shouimg::insert($add)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '收藏失败！');
        }
        //增加收藏次数
        if (!Img::where(['id' => $sucaiInfo['id']])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计灵感一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shouling', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $sucaiInfo['uid'], $sucaiInfo['id'], 6)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Img::where(['id' => $sucaiInfo['id']])->value('shoucang');
    }

    /**
     * 取消收藏素材图片.
     *
     * @param $sucaiInfo
     * @param $uid
     */
    public function deleteCollectSucaiImg($sucaiInfo, $uid): int|null
    {
        $shouLingInfo = Shouimg::where(['uid' => user()['id'], 'iid' => $sucaiInfo['id']])->first();

        if (empty($shouLingInfo)) {
            return $sucaiInfo['shoucang'];
        }
        Db::beginTransaction();

        if (!Shouimg::where(['uid' => user()['id'], 'lid' => $sucaiInfo['id']])->delete()) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //减少收藏次数
        if (!Img::where(['id' => $sucaiInfo['id']])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计灵感一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shouling', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $sucaiInfo['uid'], $sucaiInfo['id'], 10)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Img::where(['id' => $sucaiInfo['id']])->value('shoucang');
    }

    /**
     * 素材详情页面广告.
     */
    public function getSuCaiAdvertisement()
    {
        return Sucaiguanggao::query()->orderBy('id', 'desc')->get()->toArray();
    }

    /**
     * 7天/周下载数量统计.
     * @param mixed $sucaiInfo
     */
    public function recodeWeekDownNum($sucaiInfo)
    {
        //沿用旧版本计算周数
        $week = $this->getweek();
        //如果不是本周的话
        if ($week != $sucaiInfo['week']) {
            $save                = [];
            $save['week']        = $week;
            $save['weekguanzhu'] = 1;
            $ids                 = Img::query()->where(['id' => $sucaiInfo['id']])->update($save);
        } else {
            $ids = Img::query()->where(['id' => $sucaiInfo['id']])->increment('weekguanzhu');
        }
        return $ids;
    }

    /**
     * 删除素材.
     */
    public function deleteImg(int $id): mixed
    {
        return Img::query()->where('id', $id)->delete();
    }
}
