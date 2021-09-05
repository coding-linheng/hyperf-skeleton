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
 * @property int $num 下载次数
 * @property string $lid id集群
 * @property int $time 时间
 */
class Daydown extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'daydown';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'num', 'lid', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
