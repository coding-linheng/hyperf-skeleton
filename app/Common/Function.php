<?php

declare(strict_types=1);

use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\RedisProxy;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

if (!function_exists('user')) {
    /**
     * jwt用户信息.
     */
    function user()
    {
        $request = ApplicationContext::getContainer()->get(ServerRequestInterface::class);
        return $request->getAttribute('user', []);
    }
}

if (!function_exists('di')) {
    /**
     * 获取di容器
     */
    function di(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (!function_exists('redis')) {
    /**
     * 获取redis连接池实例
     */
    function redis($name = 'default'): RedisProxy
    {
        return di()->get(RedisFactory::class)->get($name);
    }
}

if (!function_exists('logger')) {
    /**
     * 获取指定日志实例
     * @param string $name
     * @param string $group
     * @return LoggerInterface
     */
    function logger(string $name = 'hyperf', string $group = 'default'): LoggerInterface
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}
