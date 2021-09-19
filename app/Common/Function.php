<?php

declare(strict_types=1);

use Hyperf\HttpServer\Contract\RequestInterface;
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
     * 获取di容器.
     */
    function di(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (!function_exists('redis')) {
    /**
     * 获取redis连接池实例.
     */
    function redis(string $name = 'default'): RedisProxy
    {
        return di()->get(RedisFactory::class)->get($name);
    }
}

if (!function_exists('logger')) {
    /**
     * 获取指定日志实例.
     */
    function logger(string $name = 'hyperf', string $group = 'default'): LoggerInterface
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (!function_exists('request')) {
    /**
     * 获取请求实例.
     */
    function request(): RequestInterface
    {
        return di()->get(RequestInterface::class);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端ip.
     * @throws TypeError
     */
    function get_client_ip(): string
    {
        return request()->getHeaderLine('x-real-ip') ?: request()->server('remote_addr');
    }
}
