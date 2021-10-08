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
    protected static int $count = 5;   //有效验证次数

    protected static int $expire = 3600; //验证码有效时间

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
                'create_time' => time(),
            ]);
            return true;
        } catch (InvalidArgumentException|NoGatewayAvailableException $e) {
            throw new BusinessException(ErrorCode::ERROR, $e->getMessage());
        }
    }

    /**
     * 检查验证码
     */
    public static function check(string $mobile, string $code, string $event = 'verify'): bool
    {
        /** @var SmsModel $sms */
        $sms = SmsModel::query()->where(['mobile' => $mobile, 'event' => $event])->orderBy('id', 'desc')->first();

        if (empty($sms)) {
            throw new BusinessException(ErrorCode::ERROR,'验证码错误');
        }

        if ($sms['create_time'] < time() - self::$expire || $sms['count'] > self::$count) {
            self::flush($mobile, $event);
            throw new BusinessException(ErrorCode::ERROR,'验证码已过期');
        }

        if ($sms['code'] != $code) {
            ++$sms->count;
            $sms->save();
            throw new BusinessException(ErrorCode::ERROR,'验证码错误');
        }
        return true;
    }

    /**
     * 清除验证码
     */
    public static function flush(string $mobile, string $event = 'verify'): bool
    {
        SmsModel::query()->where(['mobile' => $mobile, 'event' => $event])->delete();
        return true;
    }
}
