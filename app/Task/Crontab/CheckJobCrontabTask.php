<?php

declare(strict_types=1);

namespace App\Task\Crontab;

use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

///**
// * Class CheckJobCrontabTask.
// * @Crontab(name="CheckJobCrontabTask", rule="*\/1 * * * *", callback="execute", memo="定时检查未处理的日志以及其他未完成的任务")
// */
class CheckJobCrontabTask
{
    /**
     * @Inject
     * @var StdoutLoggerInterface
     */
    protected $logger;

    public function execute()
    {
        $startTime        = time();
        $isDoneCheckArr[] = [];
        echo 'start crontab CheckJobCrontabTask' . date('Y-m-d H:i:s');
        go(function () use ($isDoneCheckArr) {
//            $accountArr       = array_column($isDoneCheckArr, null, 'wechat_id');
            $accountWeChatIds = array_column($isDoneCheckArr, 'wechat_id');
        });
    }
}
