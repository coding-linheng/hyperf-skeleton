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
}
