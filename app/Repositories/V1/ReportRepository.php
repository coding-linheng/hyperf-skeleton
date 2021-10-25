<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Jubao;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;

/**
 * 举报/投诉库.
 */
class ReportRepository extends BaseRepository
{
    /**
     * 增加投诉.
     * @return Builder|Model
     */
    public function addReport(array $data): Model|Builder
    {
        return Jubao::query()->create($data);
    }
}
