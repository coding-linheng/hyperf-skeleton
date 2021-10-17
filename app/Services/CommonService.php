<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\V1\CommonRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * RequestLogService
 * 用户相关逻辑.
 */
class CommonService extends BaseService
{
    #[Inject]
    protected CommonRepository $commonRepository;

    public function getBannerIndex(): array
    {
        return $this->commonRepository->getBannerIndex();
    }

    public function getAdvertisement(): array
    {
        return $this->commonRepository->getAdvertisement();
    }

    public function getIndexTopAdvertisement(): array
    {
        return $this->commonRepository->getIndexTopAdvertisement();
    }

    public function getBlogRoll(): array
    {
        return $this->commonRepository->getBlogRoll();
    }
}
