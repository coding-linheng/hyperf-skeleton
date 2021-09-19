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
}
