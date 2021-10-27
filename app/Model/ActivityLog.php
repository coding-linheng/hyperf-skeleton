<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $user_id 用户id
 * @property int $gift 奖励内容
 * @property int $type 奖励类型 1-素材 2-文库
 * @property int $create_time 奖励时间
 */
class ActivityLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'user_id', 'gift', 'type', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'gift' => 'integer', 'type' => 'integer', 'create_time' => 'integer'];
}
