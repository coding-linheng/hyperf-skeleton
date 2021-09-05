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
 * @property int $id 主键
 * @property int $member_id 执行会员id
 * @property string $username 用户名
 * @property string $ip 执行行为者ip
 * @property string $name 行为名称
 * @property string $describe 描述
 * @property string $url 执行的URL
 * @property int $status 状态
 * @property int $update_time
 * @property int $create_time 执行行为的时间
 */
class ActionLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'action_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'member_id', 'username', 'ip', 'name', 'describe', 'url', 'status', 'update_time', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'member_id' => 'integer', 'status' => 'integer', 'update_time' => 'integer', 'create_time' => 'integer'];
}
