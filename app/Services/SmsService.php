<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\Sms;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;

/**
 * 短信相关逻辑.
 */
class SmsService extends BaseService
{
    /**
     * 根据事件检查手机号状态
     */
    public function checkMobile(string $mobile, string $event): bool
    {
        $status  = User::query()->where('mobile', $mobile)->exists();
        $message = '';
        switch ($event) {
            case 'verify':
            case 'register':
                if (!empty($status)) {
                    $message = '手机号已存在';
                }
                break;
            default:
                $message = '发送失败,请稍后再试';
                break;
        }

        if (!empty($message)) {
            throw new BusinessException(ErrorCode::ERROR, $message);
        }
        return true;
    }

    /**
     * 发送短信
     * @param string $mobile 手机号
     * @param string $event 事件
     */
    public function send(string $mobile, string $event): bool
    {
        return sms::send($mobile, $event);
    }

    /**
     * 检查验证码
     */
    public function check(string $mobile, string $code, string $event = 'verify'): bool
    {
        return sms::check($mobile, $code, $event);
    }
}
