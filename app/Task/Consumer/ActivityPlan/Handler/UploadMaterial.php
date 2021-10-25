<?php

declare(strict_types=1);

namespace App\Task\Consumer\ActivityPlan\Handler;

use App\Constants\UserCenterStatus;
use App\Repositories\V1\ActivityRepository;
use App\Repositories\V1\MessageRepository;
use App\Repositories\V1\SucaiRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WaterDoRepository;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * 上传素材活动.
 */
class UploadMaterial
{
    #[Inject]
    protected SucaiRepository $sucaiRepository;

    #[Inject]
    protected ActivityRepository $activityRepository;

    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    #[Inject]
    protected MessageRepository $messageRepository;

    //todo 后续处理到数据库 后台配置
    private array $giftArr = [5 => 1, 20 => 7, 50 => 30, 300 => 365]; //奖励数组

    public function __invoke(array $data): bool
    {
        Db::beginTransaction();
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
            $count  = $this->sucaiRepository->totalImgCount($where); //统计素材上传通过数量

            if (!array_key_exists($count, $this->giftArr)) {
                return true;
            }
            $gift = $this->giftArr[$count];
            //todo 增加活动收益日志表
            //查询当月的素材上传通过记录
            $where  = [
                ['user_id', '=', $userid],
                ['type', '=', 1],
                ['gift', '=', $gift],
                ['create_time', '>=', strtotime('first day of this month')],
                ['create_time', '<', strtotime('first day of next month')],
            ];
            $status = $this->activityRepository->getActivityGiftExists($where);

            if (!empty($status)) {
                //已经领取直接跳过
                return true;
            }
            //增加相关上传奖励及日志记录
            $data = [
                'user_id'     => $userid,
                'gift'        => $gift,
                'type'        => 1,
                'create_time' => time(),
            ];
            $this->activityRepository->insertActivityLog($data);
            $where = ['type' => 3, 'uid' => $userid];
            $this->incUserVip($where, $gift);
            Db::commit();
        } catch (\Throwable $e) {
            Db::rollBack();
            $message = sprintf('%s[%s] in %s', $e->getMessage(), $e->getLine(), $e->getFile());
            logger('upload_material', 'queue')->debug($message);
        }
        return true;
    }

    private function incUserVip(array $where, int $gift)
    {
        //判断会员是否过期
        $vip  = $this->userRepository->getUserVip($where);
        $time = $vip['time'] ?? time();

        if (empty($vip) || $time < time()) {
            $time = time();
        }
        $data    = ['time' => $time + $gift * 86400, 'vip' => 6]; //改为普通素材vip
        $message = sprintf('您的%s天素材普通vip权限现已生效,普通vip可以免费下载8款共享素材+8款原创素材,请您尽快使用,避免过期,如未使用,过期不补', $gift);
        $this->waterDoRepository->incUserVip($where, $data); //给用户增加vip时间
        $this->messageRepository->sendPrivateMessage([
            'pid'   => $where['uid'],
            'title' => '恭喜获得上传素材奖励',
            'des'   => $message,
            'time'  => time(),
        ]);
    }
}
