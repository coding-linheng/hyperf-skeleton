<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Core\Services;

use App\Core\Constants\ErrorCode;
use App\Core\Exception\BusinessException;
use App\Model\Album;
use App\Model\User;
use Hyperf\DbConnection\Db;
/**
 * AlbumService
 *
 * @property \APP\Model\User $userModel
 */
class AlbumService extends BaseService
{
    /**
     * 获取分页列表.
     */
    public function getList($queryData)
    {
        $albumResInfo = Album::query()->where($queryData)->paginate();
        return $albumResInfo;
    }

    /**
     * 自定义随机分页列表.
     */
    public function getListPageRand($queryData)
    {

      $sql= "SELECT * FROM dczg_albumlist as l where l.del<=1 and (l.is_color=1 or l.color_id=1 or l.yid=0)";
      $sql.=" and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_albumlist)-(SELECT MIN(id) FROM dczg_albumlist)) + (SELECT MIN(id) FROM dczg_albumlist))) limit 0,40";
      $users = Db::select($sql,[]);  //  返回array

      //      foreach($users as $user){
      //        echo $user->name;
      //      }

      return $users;
    }
}
