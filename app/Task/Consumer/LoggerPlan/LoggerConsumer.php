<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan;

use App\Task\Consumer\ConsumerProcess;
use App\Task\Consumer\LoggerPlan\Handler\RecordRequestLog;
use Hyperf\Process\Annotation\Process;

/**
 * 号计划-队列消费者进程.
 *
 * @Process
 */
class LoggerConsumer extends ConsumerProcess
{
    protected $queue = 'logger_plan';

    protected array $handlers = [
        'RECORD_REQUEST_LOG' => RecordRequestLog::class,
    ];
}
