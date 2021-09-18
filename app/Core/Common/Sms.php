<?php

declare(strict_types=1);

namespace App\Core\Common;

use HyperfLibraries\Sms\Contract\SmsInterface;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

class Sms
{
    /**
     * 发送短信
     */
    public static function send(string $mobile, string $event): bool
    {
        try {
            $easySms = di()->get(SmsInterface::class);
            $code    = mt_rand(100000, 999999);
            $easySms->send($mobile, [
                'template' => 'SMS_119911016',
                'data'     => [
                    'code' => $code,
                ],
            ]);
            //todo 记录短信事件入库
            return true;
        } catch (InvalidArgumentException|NoGatewayAvailableException) {
            return false;
        }
    }
}
