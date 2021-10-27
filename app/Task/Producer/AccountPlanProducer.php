<?php

declare(strict_types=1);

namespace App\Task\Producer;

class AccountPlanProducer extends BaseProducer
{
    /**
     * 用户相关队列.
     */
    protected string $queueName = 'account_plan';

    /**
     * 素材过期检查队列.
     */
    public function checkExpireImg(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'CHECK_EXPIRE_IMG',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }

    /**
     * 文库过期检查队列.
     */
    public function checkExpireLibrary(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'CHECK_EXPIRE_Library',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }
}
