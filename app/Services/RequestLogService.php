<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\V1\RequestLogRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * RequestLogService
 * 用户相关逻辑.
 */
class RequestLogService extends BaseService
{
    #[Inject]
    protected RequestLogRepository $requestLogRepository;

    public function Insert($data): int
    {
        return $this->requestLogRepository->Insert($data);
    }
}
