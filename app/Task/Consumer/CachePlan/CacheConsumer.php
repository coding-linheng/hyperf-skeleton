<?php

declare(strict_types=1);

namespace App\Task\Consumer\CachePlan;

use App\Task\Consumer\CachePlan\Scheduler\CachePicture;
use App\Task\Consumer\ConsumerProcess;
use App\Task\Consumer\LoggerPlan\Handler\RecordRequestLog;
use Hyperf\Process\Annotation\Process;

/**
 * 号计划-队列消费者进程.
 *
 * @Process
 */
class CacheConsumer extends ConsumerProcess
{
    protected $queue = 'cache_plan';

    protected array $handlers = [
        'CACHE_PIC' => CachePicture::class,
    ];
}
