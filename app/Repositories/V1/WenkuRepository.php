<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ImgSizeStyle;
use App\Model\Advertising;
use App\Model\Shouwen;
use App\Model\Wenku;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

/**
 * 文库.
 */
class WenkuRepository extends BaseRepository
{
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
            //找到目录列表
            $muluArr = $this->getMulu();

            foreach ($randListArr as $key => &$val) {
                $tmp['id']          = $val->id ?? 0;
                $tmp['path']        = get_img_path($val->path, ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']       = $val->title    ?? '';
                $tmp['shoucang']    = $val->shoucang ?? 0;
                $tmp['price']       = $val->price    ?? 0;
                $tmp['leixing']     = $val->leixing  ?? 0;
                $tmp['downnum']     = $val->downnum  ?? 0;
                $tmp['dtime']       = $val->dtime    ?? 0;
                $tmp['mulu']        = isset($val->mulu_id) && isset($muluArr[$val->mulu_id]) ? $muluArr[$val->mulu_id] : '';
                $randListArr[$key]  = $tmp;
                $tmp                = [];
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
     * 是否关收藏图片.
     * @param mixed $uid
     * @param mixed $targetId
     */
    public function isShouCang($uid, $targetId): Model|null
    {
        return Shouwen::query()->where(['uid' => $uid, 'wid' => $targetId])->first();
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
     * @param mixed $id
     */
    public function getDetailInfoById($id): Model|Builder|null
    {
        return Db::table('wenku as w')->leftJoin('user as u', 'u.id', '=', 'w.uid')->where('w.id', $id)->select(['w.*', 'u.nickname as username', 'u.imghead'])->first();
    }

    /**
     * 获取文库信息列表，待筛选展示.
     */
    public function getSearchWenkuList(array $query): array
    {
        $where    = ['status' => 3, 'del' => 0];
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

        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 20;
        $orm      = Db::table('wenku as w')->leftJoin('user as u', 'u.id', '=', 'w.uid')->where($where);

        if (!empty($query['query'])) {
            $orm = $orm->where('w.title', 'like', '%' . $query['query'] . '%');
        }

        $count    = $orm->count();
        $list     = $orm->select(['w.id', 'w.img', 'w.title', 'w.price', 'w.price', 'w.shoucang', 'w.downnum', 'w.looknum', 'w.pdfimg', 'w.leixing', 'u.nickname as username', 'u.imghead'])->orderBy($order, 'desc')->orderBy('w.id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();

        if (empty($list)) {
            return ['count' => 0, 'list' => []];
        }

        foreach ($list as &$v) {
            if (!empty($v->img)) {
                $pdfimg    = $this->getPicturejson($v->img);
                $v->pdfimg = $pdfimg;
            } else {
                $v->pdfimg = '/' . $v->pdfimg;
            }
        }
        return ['count' => $count, 'list' => $list->toArray()];
    }

    //获取详情图片地址
    public function getImgsUrl($imgs, $suffix = ImgSizeStyle::ALBUM_LIST_SMALL_PIC)
    {
        $pictures   = json_decode($imgs, true);
        $pictureTmp = [];

        if (is_array($pictures)) {
            foreach ($pictures as $pid) {
                $pictureTmp[] =  $this->getPictureById($pid, $suffix);
            }
        }
        return $pictureTmp;
    }
}
