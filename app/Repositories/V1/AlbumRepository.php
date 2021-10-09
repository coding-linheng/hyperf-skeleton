<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ImgSizeStyle;
use App\Model\Album;
use App\Model\Albumlist;
use App\Model\Img;
use App\Model\Wenku;
use App\Repositories\BaseRepository;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

/*
 * 专辑库
 */

class AlbumRepository extends BaseRepository
{
    /**
     * 获取分页列表.
     */
    public function getList(mixed $queryData): LengthAwarePaginatorInterface
    {
        return Album::query()->where($queryData)->paginate();
    }

    /**
     * 自定义随机分页列表.
     */
    public function getListPageRand(mixed $queryData): array
    {
        $sql         = 'SELECT * FROM dczg_albumlist as l where l.del<=1 and (l.is_color=1 or l.color_id=1 or l.yid=0)';
        $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_albumlist)-(SELECT MIN(id) FROM dczg_albumlist)) + (SELECT MIN(id) FROM dczg_albumlist))) limit 0,40';
        $randListArr = Db::select($sql, []);
        //处理数据
        if (!empty($randListArr)) {
            foreach ($randListArr as $key => &$val) {
                $tmp['id']         = $val->id ?? 0;
                $tmp['path']       = get_img_path($val->path,ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']      = $val->title   ?? '';
                $tmp['looknum']    = $val->looknum ?? 0;
                $tmp['downnum']    = $val->downnum ?? 0;
                $tmp['dtime']      = $val->dtime   ?? 0;
                $randListArr[$key] = $tmp;
                $tmp               = [];
            }
        }
        return $randListArr;
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     *
     * @param $query
     *
     * @param $order
     *
     * @return mixed
     */
    public function searchAlbumList($query, $order)
    {
        //return Albumlist::search()->where("title",$query)->paginate(200);
        //return Albumlist::search($query)->paginate(200);

        $queryArr = ['title' => ['or', "{$query}"], 'label' => ['or', "{$query}"]];
        $orm      = Albumlist::search($query, es_callback($queryArr));

        if (!empty($order)) {
            $orm = $orm->orderBy($order, 'desc');
        }
        $list = $orm->paginateRaw(200)->toArray();
        $list = format_es_page_raw_data($list);
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
            foreach ($list['data'] as $key => &$val) {
                if (!isset($val['id']) || empty($val['title'])) {
                    unset($list['data'][$key]);
                    continue;
                }
                $tmp['id']          = $val['id'] ?? 0;
                $tmp['path']        = get_img_path($val['path'],ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']       = $val['title']   ?? '';
                $tmp['looknum']     = $val['looknum'] ?? 0;
                $tmp['downnum']     = $val['downnum'] ?? 0;
                $tmp['dtime']       = $val['dtime']   ?? 0;
                $list['data'][$key] = $tmp;
                $tmp                = [];
            }
        }
        return $list;
    }

    /**
     * 获取专辑-专辑列表关联数据.
     */
    public function getAlbumDetailList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 100;
        $orm      = Album::from('album as a')->join('albumlist as l', 'a.id', '=', 'l.aid', 'left')->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 灵感图片详细信息，中图展示页面，获取图片，专辑，用户等信息.
     */
    public function getDetail(
        array $where,
        array $column = ['a.name as album_name ', 'a.isoriginal', 'l.*', 'u.nickname', 'u.imghead']
    ): Model|Builder|null {
        return Album::from('albumlist as l')->join('album as a', 'a.id', '=', 'l.aid', 'inner')
            ->join('user as u', 'u.id', '=', 'a.uid', 'inner')
            ->where($where)->select($column)->first();
    }

    /**
     * 获取专辑信息.
     */
    public function getAlbumDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Album::from('album as a')->join('albumlist as l', 'a.id', '=', 'l.aid', 'left')
            ->where($where)->select($column)->first();
    }

    /**
     * 获取专辑列表信息.
     */
    public function getAlbumListDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Albumlist::query()->where($where)->select($column)->first();
    }

    /**
     * 获取专辑列表信息.
     * @param mixed $query
     */
    public function getAlbumListDetailPage(array $where, $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 100;
        $orm      = Albumlist::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
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

    public function getLibraryDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenku::query()->where($where)->select($column)->first();
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
}
