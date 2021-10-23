<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $user_id 用户id
 * @property int $sign_gift 签到奖励
 * @property int $sign_time 签到时间
 * @property int $type 1-签到  2-补签
 * @property int $create_time 创建时间
 */
class SigninLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'signin_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'sign_gift', 'sign_time', 'type', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'sign_gift' => 'integer', 'sign_time' => 'integer', 'type' => 'integer', 'create_time' => 'integer'];
}
