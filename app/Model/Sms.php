<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $mobile 手机号
 * @property string $code 验证码
 * @property string $event 事件
 * @property int $count 验证次数
 * @property string $ip 请求ip
 * @property int $create_time 创建时间
 */
class Sms extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sms';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'mobile', 'code', 'event', 'count', 'ip', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'count' => 'integer', 'create_time' => 'integer'];
}
