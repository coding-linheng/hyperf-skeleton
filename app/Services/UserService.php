<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Model\Userdata;
use App\Repositories\V1\UserRepository;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;

/**
 * UserService
 * 用户相关逻辑.
 */
class UserService extends BaseService
{
    #[Inject]
    protected UserRepository $userRepository;

    /**
     * @param string $username 账号
     * @param string $password 密码
     */
    public function login(string $username, string $password): User
    {
        /** @var User $user */
        $user = User::query()->where('username', $username)->first();

        if (empty($user)) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '账号不存在');
        }

        if ($user['jinzhi'] == 2) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '您的账号因涉嫌违规操作，被系统临时冻结，如有问题，请联系客服');
        }

        if (md5($password) != $user['password']) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '密码错误');
        }
        $user->contentId = $user['id'];

        return $user;
    }

    public function getUserData(int $userid): Userdata
    {
        return $this->userRepository->getUserData($userid);
    }

    public function getUser(int $userid): User
    {
        return $this->userRepository->getUser($userid);
    }

    public function getUserMerge(int $userid, array $column = ['*']): Model
    {
        return $this->userRepository->getUserMerge($userid, $column);
    }

    /**
     * 获取用户收入数据.
     */
    public function getUserIncome(int $userid): array
    {
        return [
            'day'        => $this->userRepository->todayIncome($userid) ?? 0,
            'yesterday'  => $this->userRepository->yesterdayIncome($userid) ?? 0,
            'this_week'  => $this->userRepository->thisWeekIncome($userid) ?? 0,
            'last_week'  => $this->userRepository->lastWeekIncome($userid) ?? 0,
            'this_month' => $this->userRepository->thisMonthIncome($userid) ?? 0,
            'last_month' => $this->userRepository->lastMonthIncome($userid) ?? 0,
        ];
    }
}
