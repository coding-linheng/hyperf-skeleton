<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Wenku;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

/**
 * 文库.
 */
class WenkuRepository extends BaseRepository
{
    /**
     * 获取文库信息.
     */
    public function getLibraryStatistics(array $where): Collection|array
    {
        return Wenku::query()->where($where)->groupBy(['status'])->select(Db::raw('count(status) as count'), 'status')->get()
            ->toArray();
    }

    /**
     * 获取文库信息列表.
     */
    public function getLibraryList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $orm      = Wenku::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    public function getLibraryDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenku::query()->where($where)->select($column)->first();
    }
}
