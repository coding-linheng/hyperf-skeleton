<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Core\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 */
class ErrorCode extends AbstractConstants
{
    /**
     * @Message("Server Error！")
     */
    public const SERVER_ERROR = 500;

    public const VALIDATE_FAIL = 10000; ##验证错误

    /**
     * @Message("登录失败:用户名或密码错误")
     */
    public const LOGIN_FAIL = 10001;

    /**
     * @Message("上传失败")
     */
    public const UPLOAD_FAIL = 20001;
}
