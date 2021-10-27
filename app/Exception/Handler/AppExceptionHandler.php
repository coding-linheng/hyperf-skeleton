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

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
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
        $message = sprintf(
            '%s:%s[%s] in %s',
            $throwable->getPrevious(),
            $throwable->getMessage(),
            $throwable->getLine(),
            $throwable->getFile()
        );
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

        //判断是否验证类异常
        if ($throwable instanceof ValidationException) {
            // 阻止异常冒泡
            $this->stopPropagation();
            $format         = $this->format;
            $format['code'] = ErrorCode::VALIDATE_FAIL;
            $format['msg']  = $throwable->validator->errors()->first();
            return $response->withAddedHeader('content-type', 'application/json')
                ->withStatus(200)
                ->withBody(new SwooleStream(json_encode($format, JSON_UNESCAPED_UNICODE)));
        }
        $this->logger->error($message);
        //业务/验证报错控制台不显示相关trace记录
        $this->logger->error($throwable->getTraceAsString());
        //记录错误日志
        logger('error', 'error')->error($message);
        return $response->withHeader('Server', 'Hyperf')->withStatus(500)
            ->withBody(new SwooleStream('Internal Server Error.'));
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
