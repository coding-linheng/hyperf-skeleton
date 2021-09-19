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

namespace App\Exception\Handler;

use App\Exception\BusinessException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class AppExceptionHandler extends ExceptionHandler
{
    /**
     * @var StdoutLoggerInterface
     */
    protected $logger;

    protected $format = [
        'code' => 0,
        'msg'  => 'success',
        'data' => [],
    ];

    public function __construct(StdoutLoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $message = sprintf('%s[%s] in %s', $throwable->getMessage(), $throwable->getLine(), $throwable->getFile());
        $this->logger->error($message);
        $this->logger->error($throwable->getTraceAsString());
        // 判断是否由业务异常类抛出的异常
        if ($throwable instanceof BusinessException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            // 业务逻辑错误日志处理
            $format         = $this->format;
            $format['code'] = $throwable->getCode();
            $format['msg']  = $throwable->getMessage();
            return $response->withAddedHeader('content-type', 'application/json')
                ->withStatus(200)
                ->withBody(new SwooleStream(json_encode($format, JSON_UNESCAPED_UNICODE)));
        }

        return $response->withHeader('Server', 'Hyperf')->withStatus(500)
            ->withBody(new SwooleStream('Internal Server Error.'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}