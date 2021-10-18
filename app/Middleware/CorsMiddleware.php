<?php

declare(strict_types=1);

namespace App\Middleware;

use Hyperf\Utils\Context;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * CorsMiddleware
 * 跨域中间件
 * 本中间件做了这样一件事，让跨域请求、非跨域请求、api请求，都是用默认的session保持回话，传统请求以外的请求
 * 通过header传输sessionid来保持会话，由于框架底层写死了HYPERF_SESSION_ID这个key，所以沿用……
 * 这样做必须满足两个条件：1、服务端开启cors_access允许跨域 2、客户端实现HYPERF_SESSION_ID的存储
 * 当然如果cookie有值，优先使用cookie值
 */
class CorsMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $response = Context::get(ResponseInterface::class);
        $response = $response->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Credentials', 'true')
            // Headers 可以根据实际情况进行改写。
            ->withHeader('Access-Control-Allow-Headers', 'DNT,Keep-Alive,User-Agent,Cache-Control,Content-Type,Authorization');

        Context::set(ResponseInterface::class, $response);

        if ($request->getMethod() == 'OPTIONS') {
            return $response;
        }

        return $handler->handle($request);
    }
}
