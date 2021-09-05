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
 * @property int $uid 用户ID
 * @property int $lid 图片ID
 * @property int $cid 颜色ID
 * @property int $time 时间
 */
class Shoucolor extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shoucolor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'lid', 'cid', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer', 'lid' => 'integer', 'cid' => 'integer', 'time' => 'integer'];
}
