<?php

declare(strict_types=1);

namespace App\Task\Consumer\ActivityPlan;

use App\Task\Consumer\ActivityPlan\Handler\UploadLibrary;
use App\Task\Consumer\ActivityPlan\Handler\UploadMaterial;
use App\Task\Consumer\ConsumerProcess;
use Hyperf\Process\Annotation\Process;

/**
 * 活动相关任务-队列消费者进程.
 *
 * @Process
 */
class ActivityPlanConsumer extends ConsumerProcess
{
    protected $queue = 'activity_plan';

    protected array $handlers = [
        'UPLOAD_MATERIAL' => UploadMaterial::class,
        'UPLOAD_LIBRARY'  => UploadLibrary::class,
    ];
}
