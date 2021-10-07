<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Album;
use App\Model\Albumlist;
use App\Model\Img;
use App\Model\Wenku;
use App\Repositories\BaseRepository;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
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
        $sql = 'SELECT * FROM dczg_albumlist as l where l.del<=1 and (l.is_color=1 or l.color_id=1 or l.yid=0)';
        $sql .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_albumlist)-(SELECT MIN(id) FROM dczg_albumlist)) + (SELECT MIN(id) FROM dczg_albumlist))) limit 0,40';
        return Db::select($sql, []);  //  返回array
        //      foreach($users as $user){
        //        echo $user->name;
        //      }
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

        $queryArr=["title"=>["or","{$query}"],"label"=>["or","{$query}"]];
        $orm    = Albumlist::search($query, es_callback($queryArr));
        if (!empty($order)){
            $orm=$orm->orderBy($order, 'desc');
        }
        $list=$orm->paginateRaw(100)->toArray();
        $list = formatEsPageRawData($list);
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
            foreach ($list['data'] as $key => &$val) {
                $tmp['id']          = $val['id']??0;
                $tmp['path']        = env('PUBLIC_DOMAIN') . '/' .$val['path'];
                $tmp['title']       = $val['title']??'';
                $tmp['looknum']     = $val['looknum']??0;
                $tmp['downnum']     = $val['downnum']??0;
                $tmp['dtime']       = $val['dtime']??0;
                $list['data'][$key] = $tmp;
                $tmp                = [];
            }
        }
        return $list;
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
     * 获取文库信息.
     */
    public function getLibraryDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenku::query()->where($where)->select($column)->first();
    }

    /**
     * 获取素材信息.
     */
    public function getMaterialDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Img::query()->where($where)->select($column)->first();
    }
}
