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

namespace App\Model;

use Hyperf\Scout\Searchable;

/**
 * @property int $id 用户ID
 * @property string $nickname 昵称
 * @property string $username 用户名
 * @property string $password 密码
 * @property string $email 用户邮箱
 * @property string $mobile 用户手机
 * @property \Carbon\Carbon $update_time 更新时间
 * @property \Carbon\Carbon $create_time 注册时间
 * @property int $status 用户状态
 * @property int $leader_id 上级会员ID
 * @property int $is_share_member 是否共享会员
 * @property int $is_inside 是否为后台使用者
 */
class Member extends Model
{
    use Searchable;


    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    public const CREATED_AT = 'create_time';

    /**
     * The name of the "updated at" column.
     *
     * @var string
     */
    public const UPDATED_AT = 'update_time';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'nickname', 'username', 'password', 'email', 'mobile', 'update_time', 'create_time', 'status', 'leader_id', 'is_share_member', 'is_inside'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'update_time' => 'datetime', 'create_time' => 'datetime', 'status' => 'integer', 'leader_id' => 'integer', 'is_share_member' => 'integer', 'is_inside' => 'integer'];
}
