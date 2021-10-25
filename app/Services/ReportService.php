<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\V1\ReportRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * 举报/投诉逻辑.
 */
class ReportService extends BaseService
{
    #[Inject]
    protected ReportRepository $reportRepository;

    /**
     * 举报.
     */
    public function report(array $params): bool
    {
        return (bool)$this->reportRepository->addReport($params);
    }
}
