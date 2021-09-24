<?php

declare(strict_types=1);

namespace App\Constants;

class TaskStatus
{
    // 未开始
    public const NOT_STARTED = 1;

    // 执行中
    public const PERFORMING = 2;

    // 已完成
    public const COMPLETED = 3;

    // 暂停中
    public const SUSPEND = 4;

    // 已取消
    public const CANCELLED = 5;

    // 已失败
    public const FAILED = 6;

    public static function toArray()
    {
        return [
            self::NOT_STARTED => '未开始',
            self::PERFORMING  => '执行中',
            self::SUSPEND     => '暂停中',
            self::COMPLETED   => '已完成',
            self::CANCELLED   => '已取消',
            self::FAILED      => '已失败',
        ];
    }
}
