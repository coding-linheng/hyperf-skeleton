<?php

declare(strict_types=1);

namespace App\Task\Consumer\AccountPlan;

use App\Task\Consumer\AccountPlan\Handler\CheckExpireImg;
use App\Task\Consumer\AccountPlan\Handler\CheckExpireLibrary;
use App\Task\Consumer\ConsumerProcess;
use Hyperf\Process\Annotation\Process;

/**
 * 账号相关任务-队列消费者进程.
 *
 * @Process
 */
class AccountPlanConsumer extends ConsumerProcess
{
    protected $queue = 'account_plan';

    protected array $handlers = [
        'CHECK_EXPIRE_IMG'     => CheckExpireImg::class,
        'CHECK_EXPIRE_Library' => CheckExpireLibrary::class,
    ];
}
