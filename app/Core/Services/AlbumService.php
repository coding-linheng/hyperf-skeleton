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
      $albumResInfo = Album::query()->where($queryData)->paginate();

      return [];
    }
}
