<?php

declare(strict_types=1);

namespace App\Task\Consumer\AccountPlan;


use App\Task\Consumer\AccountPlan\Handler\Login;
use Hyperf\Process\Annotation\Process;
use App\Task\Consumer\ConsumerProcess;

/**
 * 账号相关任务-队列消费者进程
 *
 * @package App\Consumer
 * @Process()
 */
class AccountPlanConsumer extends ConsumerProcess
{
    protected $queue = 'account_plan';

    protected $handlers = [

    ];
}