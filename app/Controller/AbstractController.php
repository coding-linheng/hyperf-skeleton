<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Constants\StatusCode;
use App\Container\Response;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     *
     * @var Response
     */
    protected $response;

    /**
     * @param null|array|int|mixed|void $data
     * @param string $msg 错误消息
     */
    public function success($data = null, string $msg = 'success'): ResponseInterface
    {
        return $this->response->success($data, $msg);
    }

    /**
     * @param null|string $msg 错误消息
     * @param int $code 错误代码
     */
    public function error(?string $msg = null, int $code = ErrorCode::ERROR)
    {
        $this->response->error($code, $msg);
    }
}
