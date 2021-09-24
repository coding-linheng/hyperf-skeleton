<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Scheduler;

use App\Task\Producer\LoggerPlanProducer;
use Hyperf\Di\Annotation\Inject;

class RecordRequestLog
{
    /**
     * @Inject
     */
    protected LoggerPlanProducer $loggerPlanProducer;

    public function __invoke(array $data): bool
    {
        echo __CLASS__ . PHP_EOL;
        var_dump($data);
        $this->loggerPlanProducer->recordRequestLog($data);
        return true;
    }
}
