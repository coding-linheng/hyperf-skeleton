<?php

declare(strict_types=1);

use Yansongda\Pay\Pay;

return [
    'alipay' => [
        'default' => [
            // 支付宝分配的 app_id
            'app_id'                  => '',
            // 应用私钥
            'app_secret_cert'         => '',
            // 应用公钥证书 路径
            'app_public_cert_path'    => BASE_PATH . '/public/appCertPublicKey.crt',
            // 支付宝公钥证书 路径
            'alipay_public_cert_path' => BASE_PATH . '/public/alipayCertPublicKey.crt',
            // 支付宝根证书 路径
            'alipay_root_cert_path'   => BASE_PATH . '/public/alipayRootCert.crt',
            'return_url'              => 'https://meeting.codelin.ink/addons/epay',
            'notify_url'              => 'https://meeting.codelin.ink/addons/epay',
            'mode'                    => Pay::MODE_NORMAL,
        ],
    ],
    'wechat' => [
        'default' => [
            // 公众号 的 app_id
            'mp_app_id'               => '',
            // 小程序 的 app_id
            'mini_app_id'             => '',
            // app 的 app_id
            'app_id'                  => '',
            // 商户号
            'mch_id'                  => '',
            // 合单 app_id
            'combine_app_id'          => '',
            // 合单商户号
            'combine_mch_id'          => '',
            // 商户秘钥
            'mch_secret_key'          => '',
            // 商户私钥
            'mch_secret_cert'         => BASE_PATH . '/public/apiclient_key.pem',
            // 商户公钥证书路径
            'mch_public_cert_path'    => BASE_PATH . '/public/apiclient_cert.pem',
            // 微信公钥证书路径
            'wechat_public_cert_path' => '',
            'mode'                    => Pay::MODE_NORMAL,
        ],
    ],
    'http'   => [ // optional
        'timeout'         => 5.0,
        'connect_timeout' => 5.0,
        // 更多配置项请参考 [Guzzle](https://guzzle-cn.readthedocs.io/zh_CN/latest/request-options.html)
    ],
    'logger' => [
        'enable'   => false,
        'file'     => BASE_PATH . '/runtime/logs/pay.log',
        'level'    => 'debug',
        'type'     => 'single', // optional, 可选 daily， daily 时将按时间自动划分文件.
        'max_file' => 30, // optional, 当 type 为 daily 时有效，默认 30 天
    ],
];
