<?php

declare(strict_types=1);

namespace App\Task\Consumer;

use App\Constants\TaskType;
use App\Task\Consumer\AccountPlan\Scheduler\Login;
use App\Task\Consumer\LoggerPlan\Scheduler\RecordRequestLog;
use Hyperf\Process\Annotation\Process;

/**
 * 任务调度-队列消费者进程.
 *
 * @Process
 */
class TaskSchedulerConsumer extends ConsumerProcess
{
    protected $queue = 'task_scheduler';

    protected array $handlers = [
        TaskType::ACCOUNT_LOGIN                    => Login::class,
        TaskType::RECORD_REQUEST_LOG               => RecordRequestLog::class,
    ];
}
