<?php

declare(strict_types=1);

namespace App\Container;

use App\Exception\BusinessException;
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
     * @param mixed $data
     */
    public function success(mixed $data = null, ?string $msg = 'success'): PsrResponseInterface
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
     */
    public function error(int $code, ?string $msg = null)
    {
        throw new BusinessException($code, $msg);
    }
}
