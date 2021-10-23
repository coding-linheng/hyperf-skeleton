<?php

declare(strict_types=1);

namespace App\Services;

/*
 * 活动/签到逻辑类
 */

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Signin;
use App\Repositories\V1\ActivityRepository;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class ActivityService extends BaseService
{
    #[Inject]
    protected ActivityRepository $activityRepository;

    /** @var array 签到奖励数组 */
    private array $giftArr = [
        7  => ['type' => 2, 'day' => 1], //连续7天给vip一天
        15 => ['type' => 3, 'day' => 1], //连续7天给vip一天
        30 => ['type' => 2, 'day' => 3], //连续7天给vip一天
    ];

    //签到赠送积分
    private int $giftScore = 50;

    public function signIn(int $userid): bool
    {
        Db::beginTransaction();
        try {
            //判断今日是否已经签到
            $where = [
                ['sign_time', '>=', strtotime('today')],
                ['sign_time', '<', strtotime('tomorrow')],
                ['user_id', '=', $userid],
            ];

            if ($this->activityRepository->getSignTodayLog($where)) {
                throw new BusinessException(ErrorCode::ERROR, '今日已签到');
            }
            //更改签到信息
            $sign = $this->updateSignInfo($userid);
            //增加签到日志
            $this->addSignLog($userid, 50, 1);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '签到失败');
        }

        return true;
    }

    /**
     * 修改签到信息.
     * @param mixed $userid
     */
    private function updateSignInfo($userid): Model|Signin
    {
        $where = ['user_id' => $userid];
        $sign  = $this->activityRepository->getSignInfo($where);
        $data  = [
            'days'             => $sign ? ++$sign->days : 1,
            'total_days'       => $sign ? ++$sign->total_days : 1,
            'last_signin_time' => time(),
        ];
        return $this->activityRepository->updateSignInfo($where, $data);
    }

    /**
     * 签到日志.
     * @param mixed $userid
     * @param mixed $gift
     * @param mixed $type
     */
    private function addSignLog($userid, $gift, $type): void
    {
        $data = [
            'user_id'     => $userid,
            'sign_gift'   => $gift,
            'sign_time'   => time(),
            'type'        => $type,
            'create_time' => time(),
        ];
        $this->activityRepository->insertSignLog($data);
    }
}
