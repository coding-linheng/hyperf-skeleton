<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Album;
use App\Repositories\BaseRepository;
use Hyperf\Contract\LengthAwarePaginatorInterface;

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
        $albumResInfo = Album::query()->where($queryData)->paginate();
        return [];
    }
}
