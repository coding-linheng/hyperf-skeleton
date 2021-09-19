<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Sms as SmsModel;
use HyperfLibraries\Sms\Contract\SmsInterface;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;

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
            //验证码入库
            SmsModel::create([
                'code'        => $code,
                'mobile'      => $mobile,
                'ip'          => get_client_ip(),
                'event'       => $event,
                'create_time' => time(),
            ]);
            $easySms->send($mobile, [
                'template' => 'SMS_119911016',
                'data'     => [
                    'code' => $code,
                ],
            ]);
            return true;
        } catch (InvalidArgumentException|NoGatewayAvailableException $e) {
            throw new BusinessException(ErrorCode::ERROR, $e->getMessage());
        }
    }
}
