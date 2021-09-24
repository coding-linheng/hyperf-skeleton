<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan;


use App\Task\Consumer\LoggerPlan\Handler\RecordRequestLog;
use Hyperf\Process\Annotation\Process;
use App\Task\Consumer\ConsumerProcess;

/**
 * 号计划-队列消费者进程
 *
 * @package App\Consumer
 * @Process()
 */
class LoggerConsumer extends ConsumerProcess
{
    protected $queue = 'logger_plan';

    protected $handlers = [
        'RECORD_REQUEST_LOG'        => RecordRequestLog::class,
    ];
}