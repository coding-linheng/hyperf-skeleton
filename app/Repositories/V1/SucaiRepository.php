<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Img;
use App\Model\Shouimg;
use App\Model\Userdata;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

/**
 * 素材.
 */
class SucaiRepository extends BaseRepository
{
    /**
     * 获取素材信息.
     */
    public function getSucaiImgInfo(array $where, array $column = ['*']): Model|Builder|null
    {
        return Img::query()->where($where)->select($column)->first();
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
     * 获取素材信息.
     */
    public function getMaterialDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Img::query()->where($where)->select($column)->first();
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
        $shouLingInfo = Img::where(['uid' => user()['id'], 'lid' => $sucaiInfo['id']])->first()->toArray();

        if (!empty($shouLingInfo)) {
            return $sucaiInfo['shoucang'];
        }

        Db::beginTransaction();
        $add             = [];
        $add['uid']      = $uid;
        $add['iid']      = $sucaiInfo['id'];
        $add['img_url']  = $sucaiInfo['path'];
        $add['album_id'] = $sucaiInfo['leixing'];
        $add['img_uid']  = $sucaiInfo['uid'];
        $add['c_time']   = time();
        $add['remark']   = $remark;

        if (!Shouimg::insertGetId($add)) {
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
        $shouLingInfo = Img::where(['uid' => user()['id'], 'lid' => $sucaiInfo['id']])->first()->toArray();

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
}
