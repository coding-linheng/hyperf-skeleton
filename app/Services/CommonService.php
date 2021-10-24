<?php

declare(strict_types=1);

namespace App\Services;

use App\Repositories\V1\CommonRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * CommonService
 * 首页及相关公共部分逻辑.
 */
class CommonService extends BaseService
{
    #[Inject]
    protected CommonRepository $commonRepository;

    /**
     * 首页轮播图.
     */
    public function getBannerIndex(): array
    {
        return $this->commonRepository->getBannerIndex();
    }

    /**
     * 推荐作品
     * @param mixed $type
     */
    public function getRecommendZpList($type): array
    {
        return $this->commonRepository->getRecommendZpList($type);
    }

    /**
     * 推荐用户.
     * @param mixed $type
     */
    public function getRecommendUserList($type): array
    {
        return $this->commonRepository->getRecommendUserList($type);
    }

    /**
     * 首页广告.
     */
    public function getAdvertisement(): array
    {
        return $this->commonRepository->getAdvertisement();
    }

    /**
     * 首页顶部广告.
     */
    public function getIndexTopAdvertisement(): array
    {
        return $this->commonRepository->getIndexTopAdvertisement();
    }

    /**
     * 友情链接.
     */
    public function getBlogRoll(): array
    {
        return $this->commonRepository->getBlogRoll();
    }
}
