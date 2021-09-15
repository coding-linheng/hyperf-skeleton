<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * 文件描述
 *
 * User：Willion
 * Date：2021/9/15
 */

namespace App\Core\Repositories\Index;

use App\Core\Repositories\BaseRepository;
use App\Core\Services\AlbumService;

/**
 * 类的介绍.
 *
 */
class AlbumRepository extends BaseRepository
{
  #[Inject]
  protected AlbumService $albumService;

    /**
     * getList
     * @param $id
     * @return null|\Hyperf\Database\Model\Model|array
     */
    public function getListPageRand($queryData)
    {
        $res = $this->albumService->getListPageRand($queryData);
        return $res;
    }
}
