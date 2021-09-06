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
     * 调用responseInterface方法
     * @param $name
     * @param $arguments
     * @return PsrResponseInterface
     */
    public function __call($name, $arguments): PsrResponseInterface
    {
        return $this->response->{$name}(...$arguments);
    }

    /**
     * success响应  方便以后扩展
     * @param $data
     * @param string $msg
     * @return PsrResponseInterface
     */
    public function success($data, string $msg = 'success'): PsrResponseInterface
    {
        $data = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($data);
    }

    /**
     * error响应  方便以后扩展
     * @param $data
     * @param string $msg
     * @return PsrResponseInterface
     */
    public function error($data, string $msg = 'error'): PsrResponseInterface
    {
        $data = [
            'code' => 1,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($data);
    }
}
