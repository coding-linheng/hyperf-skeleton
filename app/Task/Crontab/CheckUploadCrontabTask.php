<?php

declare(strict_types=1);

namespace App\Task\Crontab;

use App\Model\User;
use App\Task\Producer\AccountPlanProducer;
use Hyperf\Crontab\Annotation\Crontab;
use Hyperf\Di\Annotation\Inject;

///**
// * Class CheckJobCrontabTask.
// * @Crontab(name="CheckUploadCrontabTask", rule="0 0 * * *", callback="execute", memo="定时删除用户上传未通过的素材和文库")
// */
class CheckUploadCrontabTask
{
    #[Inject]
    protected AccountPlanProducer $accountPlanProducer;

    /**
     * 每天0点00进行处理.
     */
    public function execute()
    {
        $users = User::query()->pluck('id')->toArray();
        $this->accountPlanProducer->checkExpireImg($users);
        $this->accountPlanProducer->checkExpireLibrary($users);
    }
}
