<?php
declare(strict_types=1);

namespace App\Middleware;

use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Utils\Contracts\Arrayable;
use Hyperf\Utils\Contracts\Jsonable;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class CoreMiddleware extends \Hyperf\HttpServer\CoreMiddleware
{
    protected $format = [
        'code' => 0,
        'msg'  => "success",
        'data' => [],
    ];

    protected function transferToResponse($response, ServerRequestInterface $request): ResponseInterface
    {
        if (is_string($response)) {
            $data = $response;
        } elseif (is_array($response) || $response instanceof Arrayable) {
            if ($response instanceof Arrayable) {
                $response = $response->toArray();
            }
            $data = $response;
        } elseif ($response instanceof Jsonable) {
            $data = (string)$response;
        } else {
            $data = $response;
        }

        $format         = $this->format;
        $format['data'] = $data;
        $response       = $format;

        return $this->response()
            ->withAddedHeader('content-type', 'application/json')
            ->withBody(new SwooleStream(json_encode($response, JSON_UNESCAPED_UNICODE)));
    }
}
