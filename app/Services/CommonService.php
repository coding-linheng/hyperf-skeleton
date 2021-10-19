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
   * 首页轮播图
   * @return array
   */
    public function getBannerIndex(): array
    {
        return $this->commonRepository->getBannerIndex();
    }

  /**
   * 推荐作品
   * @return array
   */
    public function getRecommendZpList($type): array
    {
      return $this->commonRepository->getRecommendZpList($type);
    }

   /**
   * 推荐用户
   * @return array
   */
    public function getRecommendUserList(): array
    {
      return $this->commonRepository->getRecommendUserList();
    }

  /**
   * 首页广告
   * @return array
   */
    public function getAdvertisement(): array
    {
        return $this->commonRepository->getAdvertisement();
    }

  /**
   * 首页顶部广告
   * @return array
   */
    public function getIndexTopAdvertisement(): array
    {
        return $this->commonRepository->getIndexTopAdvertisement();
    }

  /**
   * 友情链接
   * @return array
   */
    public function getBlogRoll(): array
    {
        return $this->commonRepository->getBlogRoll();
    }
}
