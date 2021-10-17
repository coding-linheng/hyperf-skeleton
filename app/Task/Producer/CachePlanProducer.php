<?php

declare(strict_types=1);

namespace App\Task\Producer;

class CachePlanProducer extends BaseProducer
{
    /**
     * 队列名称.
     */
    protected string $queueName = 'cache_plan';

    /**
     * 添加账号即注册.
     */
    public function cachePicture(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'CACHE_PIC',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }
}
