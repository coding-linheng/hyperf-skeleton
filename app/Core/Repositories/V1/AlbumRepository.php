<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 * 文件描述.
 *
 * User：Willion
 * Date：2021/9/15
 */

namespace App\Core\Repositories\Index;

use App\Core\Repositories\BaseRepository;
use App\Core\Services\AlbumService;
use Hyperf\Di\Annotation\Inject;

/**
 * 类的介绍.
 */
class AlbumRepository extends BaseRepository
{
    #[Inject]
  protected AlbumService $albumService;

    /**
     * getList.
     */
    public function getListPageRand(mixed $queryData): array
    {
        return $this->albumService->getListPageRand($queryData);
    }
}
