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
 * @property int $id
 * @property int $uid 用户ID
 * @property int $type 1灵感；2文库
 * @property int $endtime 到期时间
 * @property int $time 时间
 */
class Adminvip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminvip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'type', 'endtime', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'type' => 'integer', 'endtime' => 'integer', 'time' => 'integer'];
}
