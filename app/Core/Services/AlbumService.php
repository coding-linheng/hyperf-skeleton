<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Core\Services;

use App\Model\Album;
use App\Model\User;

/**
 * AlbumService.
 *
 * @property User $userModel
 */
class AlbumService extends BaseService
{
    /**
     * 获取分页列表.
     * @param mixed $queryData
     */
    public function getList($queryData)
    {
        return Album::query()->where($queryData)->paginate();
    }

    /**
     * 自定义随机分页列表.
     * @param mixed $queryData
     */
    public function getListPageRand($queryData)
    {
        $albumResInfo = Album::query()->where($queryData)->paginate();

        return [];
    }
}
