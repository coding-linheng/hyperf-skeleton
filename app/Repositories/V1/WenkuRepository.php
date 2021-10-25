<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\Advertising;
use App\Model\Daydown;
use App\Model\Shouwen;
use App\Model\Userdata;
use App\Model\Wenku;
use App\Model\Wenkudown;
use App\Model\Wenkudowndc;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * 文库.
 */
class WenkuRepository extends BaseRepository
{
    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /**
     * 自定义随机分页列表.
     * @param mixed $uid
     */
    public function getListPageRand($uid): array
    {
        $sql         = 'SELECT * FROM dczg_wenku as w where w.del<=1 and w.status=3 and w.uid=' . $uid;
        $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_wenku)-(SELECT MIN(id) FROM dczg_wenku)) + (SELECT MIN(id) FROM dczg_wenku))) limit 0,40';
        $randListArr = Db::select($sql, []);
        //处理数据
        if (!empty($randListArr)) {
            foreach ($randListArr as $key => &$val) {
                $tmp['id'] = $val->id ?? 0;
                //$tmp['pdfimg']       = get_img_path($val->pdfimg, ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                if (!empty($val->img)) {
                    $pdfimg        = $this->getPictureJson($val->img);
                    $tmp['pdfimg'] = $pdfimg;
                } else {
                    $tmp['pdfimg'] = '/' . $val->pdfimg;
                }
                $tmp['title']      = $val->title    ?? '';
                $tmp['shoucang']   = $val->shoucang ?? 0;
                $tmp['price']      = $val->price    ?? 0;
                $tmp['leixing']    = $val->leixing  ?? 0;
                $tmp['downnum']    = $val->downnum  ?? 0;
                $tmp['dtime']      = $val->dtime    ?? 0;
                $randListArr[$key] = $tmp;
                $tmp               = [];
            }
        }
        return $randListArr;
    }

    /**
     * 获取文库信息.
     */
    public function getLibraryStatistics(array $where): Collection|array
    {
        return Wenku::query()->where($where)->groupBy(['status'])->select(Db::raw('count(status) as count'), 'status')->get()
            ->toArray();
    }

    /**
     * 获取文库信息列表.
     */
    public function getLibraryList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $orm      = Wenku::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 增加在看次数.
     * @param mixed $id
     * @param mixed $count
     */
    public function getAdvertisement($id): Builder|Model|null
    {
        return Advertising::query()->where('id', $id)->first();
    }

    /**
     * 增加在看次数.
     * @param mixed $id
     * @param mixed $count
     */
    public function incLookNum($id, $count = 1): int
    {
        return Wenku::query()->where('id', $id)->increment('looknum', $count);
    }

    /**
     * 增加下载次数.
     * @param mixed $id
     * @param mixed $count
     */
    public function incDownNum($id, $count = 1): int
    {
        return Wenku::query()->where('id', $id)->increment('downnum', $count);
    }

    /**
     * 是否关收藏图片.
     * @param mixed $uid
     * @param mixed $targetId
     */
    public function isShouCang($uid, $targetId): Model|null
    {
        return Shouwen::query()->where(['uid' => $uid, 'wid' => $targetId])->first();
    }

    /**
     * 获取某个用户的收藏文库列表.
     */
    public function getShouCangList(mixed $uid): array
    {
        $shouWenkuInfoList = Shouwen::from('shouwen as s')
            ->leftJoin('wenku as w', 's.wid', '=', 'w.id')
            ->where(['s.uid' => $uid])->select([
                'w.id', 'w.pdfimg', 'w.title', 'w.shoucang', 'w.price', 'w.leixing', 'w.downnum', 'w.dtime', 'w.pdfimg', 'w.img',
            ])->paginate()->toArray();
        //处理数据
        if (!empty($shouWenkuInfoList) && isset($shouWenkuInfoList['data']) && !empty($shouWenkuInfoList['data'])) {
            foreach ($shouWenkuInfoList['data'] as $key => &$val) {
                $tmp['id'] = $val['id'] ?? 0;

                if (!empty($val['img'])) {
                    $pdfimg        = $this->getPictureJson($val['img']);
                    $tmp['pdfimg'] = $pdfimg;
                } else {
                    $tmp['pdfimg'] = '/' . $val['pdfimg'];
                }
                $tmp['title']                    = $val['title']    ?? '';
                $tmp['shoucang']                 = $val['shoucang'] ?? 0;
                $tmp['price']                    = $val['price']    ?? 0;
                $tmp['leixing']                  = $val['leixing']  ?? 0;
                $tmp['downnum']                  = $val['downnum']  ?? 0;
                $tmp['dtime']                    = $val['dtime']    ?? 0;
                $shouWenkuInfoList['data'][$key] = $tmp;
                $tmp                             = [];
            }
        }
        return $shouWenkuInfoList;
    }

    /**
     * 获取详情.
     */
    public function getLibraryDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenku::query()->where($where)->select($column)->first();
    }

    /**
     * 通过id获取详情.
     * @param int $id
     */
    public function getDetailInfoById($id)
    {
        return Db::table('wenku as w')->leftJoin('user as u', 'u.id', '=', 'w.uid')->where('w.id', $id)->select([
            'w.*', 'u.nickname as username', 'u.imghead',
        ])->first();
    }

    /**
     * 获取文库信息列表，待筛选展示.
     */
    public function getSearchWenkuList(array $query): array
    {
        $where = ['status' => 3, 'del' => 0];
        //排序
        if (empty($query['order'])) {
            $order = 'w.dtime';
        } else {
            $order = 'w.' . $query['order'];
        }

        //类型 1共享，2原创
        if (!empty($query['lid'])) {
            $where['w.leixing'] = $query['lid'];
        }

        if (!empty($query['uid'])) {
            $where['w.uid'] = $query['uid'];
        }

        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 20;
        $orm      = Db::table('wenku as w')->leftJoin('user as u', 'u.id', '=', 'w.uid')->where($where);

        if (!empty($query['query'])) {
            $orm = $orm->where('w.title', 'like', '%' . $query['query'] . '%');
        }
        //如果有随机排序的
        if (!empty($query['rand'])) {
            $orm = $orm->inRandomOrder();
        } else {
            $orm = $orm->orderBy($order, 'desc');
        }

        $count = $orm->count();
        $list  = $orm->select([
            'w.id', 'w.img', 'w.title', 'w.price', 'w.shoucang', 'w.downnum', 'w.looknum', 'w.pdfimg', 'w.leixing',
            'u.nickname as username', 'u.imghead',
        ])->orderBy('w.id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();

        if (empty($list)) {
            return ['count' => 0, 'list' => []];
        }

        foreach ($list as &$v) {
            if (!empty($v->img)) {
                $pdfimg    = $this->getPictureJson($v->img);
                $v->pdfimg = $pdfimg;
            } else {
                $v->pdfimg = '/' . $v->pdfimg;
            }
        }
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 7天/周下载数量统计.
     * @param mixed $sucaiInfo
     * @param mixed $info
     */
    public function recodeWeekDownNum($info)
    {
        //沿用旧版本计算周数
        $week = $this->getweek();
        //如果不是本周的话
        if ($week != $info['week']) {
            $save                = [];
            $save['week']        = $week;
            $save['weekguanzhu'] = 1;
            $ids                 = Wenku::query()->where(['id' => $info['id']])->update($save);
        } else {
            $ids = Wenku::query()->where(['id' => $info['id']])->increment('weekguanzhu');
        }
        return $ids;
    }

    /**
     * 增加当天免费文库下载次数.
     *
     * @param int $num
     */
    public function incDayDown(array $where, $num = 1): int
    {
        return Daydown::query()->where($where)->increment('num', $num);
    }

    /**
     * 增加当天免费文库下载次数.
     */
    public function addDayDown(array $data): int
    {
        return Daydown::query()->insertGetId($data);
    }

    /**
     * 获取当天免费文库下载信息.
     */
    public function getDayDown(array $where, array $column = ['*']): Model|Builder|null
    {
        return Daydown::query()->where($where)->select($column)->first();
    }

    /**
     * 获取原创文库下载信息.
     */
    public function getWenKuDownDc(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenkudowndc::query()->where($where)->select($column)->first();
    }

    /**
     * 增加原创文库下载信息.
     */
    public function addWenKuDownDc(array $data): int
    {
        return Wenkudowndc::query()->insertGetId($data);
    }

    /**
     * 修改共享文库下载信息.
     *
     * @param array $column
     */
    public function updateWenkuDownDc(array $where, array $data): int
    {
        return Wenkudowndc::query()->where($where)->update($data);
    }

    /**
     * 获取共享文库下载信息.
     */
    public function getWenKuDown(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenkudown::query()->where($where)->select($column)->first();
    }

    /**
     * 增加共享文库下载信息.
     */
    public function addWenKuDown(array $data): int
    {
        return Wenkudown::query()->insertGetId($data);
    }

    /**
     * 修改共享文库下载信息.
     *
     * @param array $column
     */
    public function updateWenkuDown(array $where, array $data): int
    {
        return Wenkudown::query()->where($where)->update($data);
    }

    /**
     * 删除文库.
     */
    public function deleteLibrary(array $ids): int
    {
        return Wenku::query()->whereIn('id', $ids)->delete();
    }

    /**
     * 收藏素材图片.
     *
     * @param $info
     * @param $uid
     *
     * @param $remark
     */
    public function collectDocument($info, $uid): int|null
    {
        $shouWenInfo = Shouwen::where(['uid' => user()['id'], 'wid' => $info['id']])->first();

        if (!empty($shouWenInfo)) {
            return $shouWenInfo['shoucang'];
        }

        Db::beginTransaction();
        $add        = [];
        $add['uid'] = $uid;
        $add['wid'] = $info['id'];

        if (!Shouwen::insert($add)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '收藏失败！');
        }
        //增加收藏次数
        if (!Wenku::where(['id' => $info['id']])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计文库一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shouwen', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $info['uid'], $info['id'], 5)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Wenku::where(['id' => $info['id']])->value('shoucang');
    }

    /**
     * 取消收藏文库.
     *
     * @param $info
     * @param $uid
     */
    public function deleteCollectDocument($info, $uid): int|null
    {
        $shouWenInfo = Shouwen::where(['uid' => user()['id'], 'wid' => $info['id']])->first();

        if (empty($shouWenInfo)) {
            return $shouWenInfo['shoucang'];
        }

        Db::beginTransaction();

        if (!Shouwen::where(['uid' => user()['id'], 'wid' => $info['id']])->delete()) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //减少收藏次数
        if (!Wenku::where(['id' => $info['id']])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计文库一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shouwen', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $info['uid'], $info['id'], 9)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Wenku::where(['id' => $info['id']])->value('shoucang');
    }

    /**
     * 统计文库数量.
     */
    public function totalWenkuCount(array $where): int
    {
        return Wenku::query()->where($where)->count();
    }
}
