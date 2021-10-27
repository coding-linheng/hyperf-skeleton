<?php

declare(strict_types=1);

namespace App\Common;

use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 支付处理类.
 */
class Pay
{
    /**
     * 微信支付回调处理.
     */
    public function wechatNotify(\Yansongda\HyperfPay\Pay $pay, ServerRequestInterface $serverRequest): Response
    {
        try {
            $notify = $pay->wechat()->callback($serverRequest);

            if ($notify['trade_state'] !== 'SUCCESS') {
                $this->error('支付失败');
            }
        } catch (\Exception $exception) {
        }

        return $pay->wechat()->success();
    }

    /**
     * 错误应答.
     */
    private function error(string $message)
    {
        di()->get(ResponseInterface::class)->json(['code' => 500, 'message' => $message]);
    }
}
