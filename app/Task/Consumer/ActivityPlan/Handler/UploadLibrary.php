<?php

declare(strict_types=1);

namespace App\Task\Consumer\ActivityPlan\Handler;

use App\Constants\UserCenterStatus;
use App\Repositories\V1\SucaiRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * 上传素材活动.
 */
class UploadLibrary
{
    #[Inject]
    protected SucaiRepository $sucaiRepository;

    //todo 后续处理到数据库 后台配置
    private array $giftArr = [5 => 1, 20 => 7, 50 => 30, 300 => 365]; //奖励数组

    public function __invoke(array $data): bool
    {
        try {
            $userid = $data['user_id'];
            $start  = strtotime('first day of this month');
            $end    = strtotime('first day of next month');
            $where  = [  //当月内通过原创素材数量
                ['uid', '=', $userid],
                ['time', '>=', $start],
                ['time', '<', $end],
                ['status', '=', UserCenterStatus::WORK_MANAGE_IS_PASS],
                ['leixing', '=', 2],
            ];
            $count  = $this->sucaiRepository->totalImgCount($where);

            if (!array_key_exists($count, $this->giftArr)) {
                return true;
            }
            //todo 增加活动收益日志表
            //查询当月的素材上传通过记录
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
        return true;
    }
}
