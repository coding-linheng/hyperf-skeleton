<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Model\User;
use App\Repositories\V1\AlbumRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * AlbumService.
 *
 * @property User $userModel
 */
class AlbumService extends BaseService
{
    #[Inject]
    protected AlbumRepository $albumRepository;

    /**
     * getList.
     */
    public function getListPageRand(mixed $queryData): array
    {
        return $this->albumRepository->getListPageRand($queryData);
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     *
     * @param mixed $queryData
     * @param $order
     *
     * @return mixed
     */
    public function searchAlbumList($queryData, $order)
    {
        return $this->albumRepository->searchAlbumList($queryData, $order);
    }
}
