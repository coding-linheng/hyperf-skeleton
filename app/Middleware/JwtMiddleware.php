<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Common\Rcp;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Qbhy\HyperfAuth\AuthManager;

class JwtMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $auth = $this->container->get(AuthManager::class);

        $isValidToken = false;
        // 根据具体业务判断逻辑走向，这里假设用户携带的token有效
        // jwt所能获得获得用户信息在此添加
        if ($auth->check() && $user = $auth->user()) {
            $user = [
                'id'       => $user['id'],
                'username' => $user['username'],
            ];
//            $rcpService = di()->get(Rcp::class);
//            //将uri 和用户丢入统计风控组件，计算是否本次应该放过同行
//            if (!$rcpService->check($request, $user)) {
//                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, 'Service Unavailable Or Refused Request !');
//            }
            $request = Context::override(
                ServerRequestInterface::class,
                function (ServerRequestInterface $request) use ($user) {
                    return $request->withAttribute('user', $user);
                }
            );

            $isValidToken = true;
        }

        if ($isValidToken) {
            return $handler->handle($request);
        }

        throw new BusinessException(ErrorCode::AUTH_ERROR, 'Token authentication does not pass');
    }
}
