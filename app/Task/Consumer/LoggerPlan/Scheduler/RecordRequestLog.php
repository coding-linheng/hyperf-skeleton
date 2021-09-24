<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Scheduler;

use App\Task\Producer\LoggerPlanProducer;
use App\Task\Producer\TaskSchedulerProducer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Codec\Json;


class RecordRequestLog
{
    /**
     * @Inject()
     * @var LoggerPlanProducer
     */
    protected $loggerPlanProducer;

    public function __invoke(array $data)
    {
        foreach ($data as $key => $account) {
            $data = [

                'ip_group_id'  => $data['ip_group_id'],
                'area' => $data['area'],
                'is_last'      => 0,
            ];
            $this->loggerPlanProducer->recordRequestLog($data);
        }
    }
}