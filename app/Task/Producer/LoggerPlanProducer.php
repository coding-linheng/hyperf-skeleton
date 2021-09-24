<?php

declare(strict_types=1);

namespace App\Task\Producer;

class LoggerPlanProducer extends BaseProducer
{
    /**
     * 队列名称.
     */
    protected string $queueName = 'logger_plan';

    /**
     * 添加账号即注册.
     */
    public function recordRequestLog(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'RECORD_REQUEST_LOG',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }
}
