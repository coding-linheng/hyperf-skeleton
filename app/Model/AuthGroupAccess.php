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

/**
 * @property int $member_id 用户id
 * @property int $group_id 用户组id
 * @property int $update_time
 * @property int $create_time
 * @property int $status
 */
class AuthGroupAccess extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth_group_access';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['member_id', 'group_id', 'update_time', 'create_time', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['member_id' => 'integer', 'group_id' => 'integer', 'update_time' => 'integer', 'create_time' => 'integer', 'status' => 'integer'];
}
