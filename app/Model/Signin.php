<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $user_id 用户id
 * @property int $days 连续签到天数
 * @property int $total_days 累计签到天数
 * @property int $last_signin_time 最后一次签到时间
 */
class Signin extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'signin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'days', 'total_days', 'last_signin_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'days' => 'integer', 'total_days' => 'integer', 'last_signin_time' => 'integer'];
}
