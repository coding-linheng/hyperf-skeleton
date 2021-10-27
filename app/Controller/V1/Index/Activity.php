<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Services\ActivityService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * 签到/活动接口.
 */
class Activity extends AbstractController
{
    #[Inject]
    protected ActivityService $activityService;

    /**
     * 签到.
     */
    public function signIn(): ResponseInterface
    {
        return $this->success($this->activityService->signIn(user()['id']));
    }
}
