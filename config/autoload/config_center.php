<?php

declare(strict_types=1);

use Hyperf\ConfigCenter\Mode;

return [
    // 是否开启配置中心
    'enable' => (bool) env('CONFIG_CENTER_ENABLE', false),
    // 使用的驱动类型，对应同级别配置 drivers 下的 key
    'driver' => env('CONFIG_CENTER_DRIVER', 'apollo'),
    // 配置中心的运行模式，多进程模型推荐使用 PROCESS 模式，单进程模型推荐使用 COROUTINE 模式
    'mode' => env('CONFIG_CENTER_MODE', Mode::PROCESS),
    'drivers' => [
        'apollo' => [
            'driver' => Hyperf\ConfigApollo\ApolloDriver::class,
            // Apollo Server
            'server' => '',
            // 您的 AppId
            'appid' => '',
            // 当前应用所在的集群
            'cluster' => 'default',
            // 当前应用需要接入的 Namespace，可配置多个
            'namespaces' => [
                'application',
            ],
            // 配置更新间隔（秒）
            'interval' => 5,
            // 严格模式，当为 false 时，拉取的配置值均为 string 类型，当为 true 时，拉取的配置值会转化为原配置值的数据类型
            'strict_mode' => false,
            // 客户端IP
            'client_ip' => current(swoole_get_local_ip()),
            // 拉取配置超时时间
            'pullTimeout' => 10,
            // 拉取配置间隔
            'interval_timeout' => 1,
        ],
    ],
];
