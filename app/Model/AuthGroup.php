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
 * @property int $id 用户组id,自增主键
 * @property string $module 用户组所属模块
 * @property string $name 用户组名称
 * @property string $describe 描述信息
 * @property int $status 用户组状态：为1正常，为0禁用,-1为删除
 * @property string $rules 用户组拥有的规则id，多个规则 , 隔开
 * @property int $member_id
 * @property int $update_time
 * @property int $create_time
 */
class AuthGroup extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'auth_group';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'module', 'name', 'describe', 'status', 'rules', 'member_id', 'update_time', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'member_id' => 'integer', 'update_time' => 'integer', 'create_time' => 'integer'];
}
