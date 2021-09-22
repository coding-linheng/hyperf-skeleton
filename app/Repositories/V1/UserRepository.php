<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
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
}
