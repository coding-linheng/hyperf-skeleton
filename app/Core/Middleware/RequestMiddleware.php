<?php

declare(strict_types=1);

namespace App\Core\Middleware;

use Hyperf\Logger\LoggerFactory;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;

class RequestMiddleware implements MiddlewareInterface
{
    protected ContainerInterface $container;

    protected LoggerInterface $logger;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->logger    = $container->get(LoggerFactory::class)->get('request', 'request');
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $params = $request->getServerParams();
        $params = array_merge($params, ['token' => $request->getHeader('authorization')[0] ?? '']);
        $this->logger->info('server_params', $params);
        $this->logger->info('request_params', $request->getParsedBody());
        return $handler->handle($request);
    }
}
