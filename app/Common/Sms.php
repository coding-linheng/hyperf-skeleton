<?php

declare(strict_types=1);

namespace App\Common;

use HyperfLibraries\Sms\Contract\SmsInterface;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use \App\Model\Sms as SmsModel;

class Sms
{
    /**
     * 发送短信
     */
    public static function send(string $mobile, string $event = 'verify'): bool
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
            //验证码入库
            SmsModel::create([
                'code'        => $code,
                'mobile'      => $mobile,
                'ip'          => get_client_ip(),
                'event'       => $event,
                'create_time' => time()
            ]);
            return true;
        } catch (InvalidArgumentException | NoGatewayAvailableException) {
            return false;
        }
    }
}
