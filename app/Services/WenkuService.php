<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Repositories\V1\WenkuRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * WenkuService.
 *
 * @property User $userModel
 */
class WenkuService extends BaseService
{
    #[Inject]
    protected WenkuRepository $wenkuRepository;

    /**
     * 模糊搜索文库数据，包含标题和关键字及其他筛选列表.
     * query 搜素关键字，热门搜索，不填为全部
     * order 排序字段： 最新 dtime，热门下载 downnum，推荐 tui
     * lid 不传该字段或者传0则表示默认全部，1共享，2原创, mulu_id：分类id.
     *
     * @param  array  $query
     *
     * @return array|null
     */
    public function getList(array $query): array|null
    {
        return $this->wenkuRepository->getSearchWenkuList($query);
    }
}
