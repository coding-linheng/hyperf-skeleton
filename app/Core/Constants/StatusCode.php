<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Core\Constants;

use Hyperf\Constants\AbstractConstants;
use Hyperf\Constants\Annotation\Constants;

/**
 * @Constants
 * 自定义业务代码规范如下：
 * 授权相关，1001……
 * 用户相关，2001……
 * 业务相关，3001……
 */
class StatusCode extends AbstractConstants
{
    /**
     * @Message("ok")
     */
    public const SUCCESS = 200;

    /**
     * @Message("Internal Server Error!")
     */
    public const ERR_SERVER = 500;

    /**
     * @Message("无权限访问！")
     */
    public const ERR_NOT_ACCESS = 1001;

    /**
     * @Message("令牌过期！")
     */
    public const ERR_EXPIRE_TOKEN = 1002;

    /**
     * @Message("令牌无效！")
     */
    public const ERR_INVALID_TOKEN = 1003;

    /**
     * @Message("令牌不存在！")
     */
    public const ERR_NOT_EXIST_TOKEN = 1004;

    /**
     * @Message("请输入谷歌验证码！")
     */
    public const ERR_NOT_GOOGLE_TOKEN = 1005;

    /**
     * @Message("请登录！")
     */
    public const ERR_NOT_LOGIN = 2001;

    /**
     * @Message("用户信息错误！")
     */
    public const ERR_USER_INFO = 2002;

    /**
     * @Message("用户不存在！")
     */
    public const ERR_USER_ABSENT = 2003;

    /**
     * @Message("业务逻辑异常！")
     */
    public const ERR_EXCEPTION = 3001;

    /**
     * 用户相关逻辑异常.
     * @Message("用户密码不正确！")
     */
    public const ERR_EXCEPTION_USER = 3002;

    /**
     * 文件上传.
     * @Message("文件上传异常！")
     */
    public const ERR_EXCEPTION_UPLOAD = 3003;
}
