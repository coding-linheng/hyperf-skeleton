<?php

declare(strict_types=1);

namespace App\Task\Consumer\CachePlan;

use App\Task\Consumer\CachePlan\Handler\CachePicture;
use App\Task\Consumer\CachePlan\Handler\ReformatMysql;
use App\Task\Consumer\ConsumerProcess;
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
        'CACHE_PIC'     => CachePicture::class,
        'REFRESH_MYSQL' => ReformatMysql::class,
    ];
}
