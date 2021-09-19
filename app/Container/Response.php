<?php

declare(strict_types=1);

namespace App\Container;

use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as PsrResponseInterface;

/**
 * @method PsrResponseInterface json($data)
 * @method PsrResponseInterface xml($data)
 * @method PsrResponseInterface raw($data, string $root = 'root')
 */
class Response
{
    #[Inject]
    protected ResponseInterface $response;

    /**
     * 调用responseInterface方法.
     * @param $name
     * @param $arguments
     */
    public function __call($name, $arguments): PsrResponseInterface
    {
        return $this->response->{$name}(...$arguments);
    }

    /**
     * success响应  方便以后扩展.
     * @param null $data
     */
    public function success($data = null, ?string $msg = 'success'): PsrResponseInterface
    {
        $data = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($data);
    }

    /**
     * error响应  方便以后扩展.
     * @param null $data
     */
    public function error(int $code = 1, ?string $msg = 'error', $data = null): PsrResponseInterface
    {
        $data = [
            'code' => $code,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($data);
    }
}
