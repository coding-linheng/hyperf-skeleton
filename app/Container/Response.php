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

    public function __call($name, $arguments): PsrResponseInterface
    {
        return $this->response->{$name}(...$arguments);
    }

    public function success($data, string $msg = 'success'): PsrResponseInterface
    {
        $data = [
            'code' => 0,
            'msg'  => $msg,
            'data' => $data,
        ];

        return $this->json($data);
    }

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
