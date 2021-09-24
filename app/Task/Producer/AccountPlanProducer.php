<?php

declare(strict_types=1);

namespace App\Task\Producer;

class AccountPlanProducer extends BaseProducer
{
    /**
     * 队列名称.
     */
    protected string $queueName = 'account_plan';

    /**
     * 添加账号即注册.
     */
    public function accountAdd(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'ACCOUNT_LOGIN',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }

    /**
     * 登陆.
     */
    public function login(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'LOGIN',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }

    /**
     * 退出.
     */
    public function logout(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'LOGOUT',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }

    /**
     * 修改密码
     */
    public function resetPassword(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'RESET_PASSWORD',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }
}
