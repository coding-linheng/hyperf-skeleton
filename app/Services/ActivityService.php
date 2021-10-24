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
use App\Repositories\V1\WaterDoRepository;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

class ActivityService extends BaseService
{
    #[Inject]
    protected ActivityRepository $activityRepository;

    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /** @var array 签到奖励数组 */
    private array $giftArr = [
        7  => ['type' => 2, 'day' => 1], //连续7天给vip一天 1-共享分 2-素材 3-文库
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

            if ($this->activityRepository->getSignLog($where)) {
                throw new BusinessException(ErrorCode::ERROR, '今日已签到');
            }
            //更改签到信息
            $sign = $this->updateSignInfo($userid);
            //增加签到日志  每日签到固定增加积分
            if (empty($this->addSignLog($userid, $this->giftScore, 1))) {
                Db::rollBack();
                throw new BusinessException(ErrorCode::ERROR, '签到失败');
            }
            //根据连续签到天数进行奖励
            $days = $sign->days ?? 1;

            if (!array_key_exists($days, $this->giftArr)) {
                //天数没有奖励直接结束
                Db::commit();
                return true;
            }
            $gift = $this->giftArr[$days];
            //判断该奖励是否已经领过
            $where = [
                ['user_id', '=', $userid],
                ['type', '=', $gift['type']],
                ['sign_gift', '=', $gift['day']],
                ['create_time', '>=', strtotime('first day of this month')],
                ['create_time', '<', strtotime('first day of next month')],
            ];
            //已领取天数奖励直接跳过
            if (empty($this->activityRepository->getSignLog($where))) {
                $this->addSignLog($userid, $gift['day'], $gift['type']);
            }
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            throw new BusinessException($e->getCode(), $e->getMessage());
        }

        return true;
    }

    /**
     * 修改签到信息.
     */
    private function updateSignInfo(int $userid): Model|Signin
    {
        $where = ['user_id' => $userid];
        $sign  = $this->activityRepository->getSignInfo($where);
        $days  = $sign ? $sign->days : 0;
        $time  = $sign ? $sign->last_signin_time : 0;
        //判断是否新的月份 新月份连续签到次数清0
        $days = date('Ym', $sign->last_signin_time) == date('Ym') ? $days : 0;
        //间隔超过一天清0
        $days = time() - $time < 86400 ? $days : 0;
        $data = [
            'days'             => $sign ? ++$days : 1,
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
    private function addSignLog(int $userid, int $gift, int $type): bool
    {
        $data = [
            'user_id'     => $userid,
            'sign_gift'   => $gift,
            'sign_time'   => time(),
            'type'        => $type,
            'create_time' => time(),
        ];
        $this->activityRepository->insertSignLog($data);
        //根据奖励增加用户相关数据
        return match ($type) {
            1 => $this->waterDoRepository->addUserScore($userid, $gift), //给用户增加积分
        };
    }
}
