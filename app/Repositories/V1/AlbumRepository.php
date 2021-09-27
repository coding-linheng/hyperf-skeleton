<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Album;
use App\Model\Albumlist;
use App\Repositories\BaseRepository;
use Hyperf\Contract\LengthAwarePaginatorInterface;
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
     * @return mixed
     */
    public function searchAlbumList($query)
    {
        //return Albumlist::search()->where("title",$query)->paginate(200);
        // Albumlist::search(" ".$query." ")->paginate(200);
        return Albumlist::search($query)->paginate(200);
    }
}
