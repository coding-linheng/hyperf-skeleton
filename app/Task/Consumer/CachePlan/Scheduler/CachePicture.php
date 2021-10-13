<?php

declare(strict_types=1);

namespace App\Task\Consumer\CachePlan\Scheduler;

use App\Task\Producer\CachePlanProducer;
use Hyperf\Di\Annotation\Inject;

class CachePicture
{
    /**
     * @Inject
     */
    protected CachePlanProducer $cachePlanProducer;

    public function __invoke(array $data): bool
    {
        $this->cachePlanProducer->cachePicture($data);
        return true;
    }
}
