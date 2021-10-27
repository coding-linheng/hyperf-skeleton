<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface;
use Yansongda\Supports\Collection;

/**
 * 支付处理类.
 */
class Pay
{
    protected \Yansongda\HyperfPay\Pay $pay;

    public function __construct(\Yansongda\HyperfPay\Pay $pay)
    {
        $this->pay = $pay;
    }

    /**
     * @param array $order 支付参数  https://pay.yansongda.cn/docs/v3
     * @param string $driver wechat alipay
     * @param string $type 支付类型
     */
    public function pay(array $order, string $driver, string $type): Response|array
    {
        $response = null;
        try {
            switch ($driver) {
                case 'wechat':
                    $response = match ($type) {
                        'mp'   => $this->pay->wechat()->mp($order),
                        'wap'  => $this->pay->wechat()->wap($order),
                        'app'  => $this->pay->wechat()->app($order),
                        'scan' => $this->pay->wechat()->scan($order),
                        'mini' => $this->pay->wechat()->mini($order),
                    };
                    break;
                case 'alipay':
                    $response = match ($type) {
                        'web'      => $this->pay->alipay()->web($order),
                        'wap'      => $this->pay->alipay()->wap($order),
                        'app'      => $this->pay->alipay()->app($order),
                        'pos'      => $this->pay->alipay()->pos($order),
                        'scan'     => $this->pay->alipay()->scan($order),
                        'transfer' => $this->pay->alipay()->transfer($order),
                        'mini'     => $this->pay->alipay()->mini($order),
                    };
                    break;
                default:
                    exception('不存在的支付引擎');
                    break;
            }
        } catch (\UnhandledMatchError) {
            exception('不存在的支付方式', ErrorCode::PAY_ERROR);
        } catch (\Throwable $e) {
            exception($e->extra['message'] ?? $e->getMessage(), ErrorCode::PAY_ERROR);
        }
        //统一处理为数组形式
        if ($response instanceof Collection) {
            $response = $response->toArray();
        } elseif ($response instanceof ResponseInterface) {
            $response = $response->getBody()->getContents();
        }
        return $response ?? $this->error('支付失败');
    }

    /**
     * 微信支付回调处理.
     */
    public function wechatNotify(\Yansongda\HyperfPay\Pay $pay, ServerRequestInterface $serverRequest): Response
    {
        //todo 待完善支付回调逻辑  等待对接获得回调参数
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
    private function error(string $message): Response
    {
        return di()->get(ResponseInterface::class)->json(['code' => 500, 'message' => $message]);
    }
}
