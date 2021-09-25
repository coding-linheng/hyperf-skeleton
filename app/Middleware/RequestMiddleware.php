<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Common\Rcp;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RequestMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!empty(env('RCP_OPEN', 0))) {
            $rcpService = di()->get(Rcp::class);
            //将uri 和用户丢入统计风控组件，计算是否本次应该放过同行
            $rcpService->check($request, []);
        }
        return $handler->handle($request);
    }
}
