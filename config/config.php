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
use Hyperf\Contract\StdoutLoggerInterface;
use Psr\Log\LogLevel;

return [
    'app_name' => env('APP_NAME', 'skeleton'),
    'app_env' => env('APP_ENV', 'dev'),
    // 是否允许跨域资源访问
    'cors_access' => env('CORS_ACCESS', true),
    // 应用域名(静态资源访问使用)
    'app_domain' => env('APP_DOMAIN', ''),
    // 允许跨域的域名
    'allow_origins' => [
        'http://127.0.0.1',
        'http://119.23.59.3',
        'http://localhost',
        'http://www.yoctometer.com',
        'http://www.yinmengkeji.com',
        'http://www.hyperfcms.com',
        'http://demo.hyperfcms.com',
        'http://cors-demo.hyperfcms.com',
    ],
    'scan_cacheable' => env('scan_cacheable', false),
    StdoutLoggerInterface::class => [
        'log_level' => [
            LogLevel::ALERT,
            LogLevel::CRITICAL,
            LogLevel::EMERGENCY,
            LogLevel::ERROR,
            LogLevel::INFO,
            LogLevel::NOTICE,
            LogLevel::WARNING,
        ],
    ],
];
