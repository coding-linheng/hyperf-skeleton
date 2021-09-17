<?php

declare(strict_types=1);

namespace App\Task\Producer;

class TaskSchedulerProducer extends BaseProducer
{
    /**
     * 队列名称.
     * @var string
     */
    protected $queueName = 'job_task_scheduler';

    public function addTask(int $taskType, array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => $taskType,
            'data' => $data,
        ];
        return $this->push($newData, $delay);
    }
}
