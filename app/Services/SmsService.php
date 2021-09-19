<?php

declare(strict_types=1);

namespace App\Services;

use App\Common\Sms;
use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Userdata;

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
        $status  = Userdata::query()->where('tel', $mobile)->exists();
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

    public function send(string $mobile, string $event): bool
    {
        return sms::send($mobile, $event);
    }
}
