<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
return [
    'default' => [
        'driver' => App\Task\AsyncQueue\MyRedisDriver::class,
        'redis' => [
            'pool' => 'default',
        ],
        'channel' => '{queue}',
        'timeout' => 2,
        'retry_seconds' => 5,
        'handle_timeout' => 10,
        'processes' => 1,
        'concurrent' => [
            'limit' => 10,
        ],
    ],
    'account_plan' => [
        'driver' => App\Task\AsyncQueue\MyRedisDriver::class,
        'channel' => 'account_plan',
        'timeout' => 2,
        'retry_seconds' => 5,
        'handle_timeout' => 10,
        'processes' => 1,
    ],
    'logger_plan' => [
        'driver'         => App\Task\AsyncQueue\MyRedisDriver::class,
        'channel'        => 'logger_plan',
        'timeout'        => 2,
        'retry_seconds'  => 5,
        'handle_timeout' => 10,
        'processes'      => 2,
    ],
    'task_scheduler' => [
        'driver'         => App\Task\AsyncQueue\MyRedisDriver::class,
        'channel'        => 'task_scheduler',
        'timeout'        => 10,
        'retry_seconds'  => 5,
        'handle_timeout' => 10,
        'processes'      => 1,
    ],

];
