<?php

declare(strict_types=1);

namespace App\Constants;

class UserCenterStatus
{
    //用户认证未提交
    public const USER_CERT_NO_SUBMIT = 0;

    //用户认证已提交
    public const USER_CERT_IS_SUBMIT = 1;

    //用户认证已通过
    public const USER_CERT_IS_PASS = 2;

    //用户认证未通过
    public const USER_CERT_NO_PASS = 3;

    //提现未审核
    public const USER_CASH_NO_VERIFY = 0;

    //提现已通过
    public const USER_CASH_IS_PASS = 1;

    //提现未通过
    public const USER_CASH_NO_PASS = 2;

    //待处理
    public const WORK_MANAGE_PENDING = 0;

    //审核中
    public const WORK_MANAGE_REVIEW = 1;

    //未通过
    public const WORK_MANAGE_NO_PASS = 2;

    //已通过
    public const WORK_MANAGE_IS_PASS = 3;

    //需调整
    public const WORK_MANAGE_REVISION = 4;
}
