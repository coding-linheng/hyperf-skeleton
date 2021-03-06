<?php
/**
 * 配置文件
 *
 * @link     http://www.swoole.red
 * @contact  1712715552@qq.com
 */

use Hyperf\Guzzle\HandlerStackFactory;

return [
    // 默认发送配置
    'default'  => [
        // 网关调用策略，默认：顺序调用
        'strategy' => \Overtrue\EasySms\Strategies\OrderStrategy::class,

        // 默认可用的发送网关
        'gateways' => ['aliyun'],
    ],
    // 可用的网关配置
    'gateways' => [
        'qcloud' => [
            'sdk_app_id' => '', // SDK APP ID
            'app_key'    => '', // APP KEY
            'sign_name'  => '', // 短信签名，如果使用默认签名，该字段可缺省（对应官方文档中的sign）
        ],
        'aliyun' => [
            'access_key_id'     => '',
            'access_key_secret' => '',
            'sign_name'         => '',
        ],
        //...
    ],
    'options'  => [
        'config' => [
            'handler' => (new HandlerStackFactory)->create([
                'min_connections' => 1,
                'max_connections' => 30,
                'wait_timeout'    => 3.0,
                'max_idle_time'   => 60,
            ]),
        ],
    ]
];
