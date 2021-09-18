<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Core\Services;

use App\Core\Repositories\V1\AlbumRepository;
use App\Model\User;
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
