<?php

declare(strict_types=1);

namespace App\Core\Services;

use App\Core\Constants\ErrorCode;
use App\Core\Exception\BusinessException;
use App\Model\User;

/**
 * UserService
 * 用户相关逻辑.
 */
class UserService extends BaseService
{
    /**
     * 用户登录.
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
}
