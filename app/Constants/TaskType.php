<?php

declare(strict_types=1);

namespace App\Constants;

class TaskType
{
    // 登录时候触发的任务
    const ACCOUNT_LOGIN = 1;
    // 测试
    const ACCOUNT_TEST = 99999;

    // 记录请求日志
    const RECORD_REQUEST_LOG = 1001;

    /**
     * 获取计划任务类型
     *
     * @return array
     */
    public static function getJobTypes()
    {
        return [
            self::ACCOUNT_LOGIN           => '登录',
            self::RECORD_REQUEST_LOG      => '记录请求日志',
        ];
    }

}