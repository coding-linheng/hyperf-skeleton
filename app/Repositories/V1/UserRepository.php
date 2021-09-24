<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Daywaterdc;
use App\Model\User;
use App\Model\Userdata;
use App\Repositories\BaseRepository;

/**
 * 用户库.
 */
class UserRepository extends BaseRepository
{
    /**
     * 获取用户data模型.
     */
    public function getUserData(int $userid): Userdata
    {
        /** @var Userdata $userData */
        $userData = Userdata::query()->where('uid', $userid)->first();

        if (empty($userData)) {
            throw new BusinessException(ErrorCode::ERROR, '用户不存在');
        }
        return $userData;
    }

    /**
     * 获取User用户.
     */
    public function getUser(int $userid): User
    {
        /** @var User $user */
        $user = User::query()->where('id', $userid)->first();

        if (empty($user)) {
            throw new BusinessException(ErrorCode::ERROR, '用户不存在');
        }
        return $user;
    }

    public function getUserMerge(int $userid, $column = ['*']): User
    {
        /** @var User $user */
        return User::from('user as u')->join('userdata as d', 'u.id', '=', 'd.uid')
            ->where('u.id', $userid)
            ->select($column)->first();
    }

    /**
     * 获取今日收益.
     */
    public function todayIncome(int $userid): ?string
    {
        $time = strtotime('today');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /**
     * 获取昨日收益.
     */
    public function yesterdayIncome(int $userid): ?string
    {
        $time = strtotime('yesterday');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取本周收益
     */
    public function thisWeekIncome(int $userid): ?string
    {
        $time = strtotime('this week sunday');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取上周周收益
     */
    public function lastWeekIncome(int $userid): ?string
    {
        $time = strtotime('last week sunday');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取本月收益
     */
    public function thisMonthIncome(int $userid): ?string
    {
        $time = strtotime(date('Y-m-d', strtotime('first day of this month')));
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取上月收益
     */
    public function lastMonthIncome(int $userid): ?string
    {
        $time = strtotime(date('Y-m-d', strtotime('first day of last month')));
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }
}
