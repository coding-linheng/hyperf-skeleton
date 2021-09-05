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
 * @property string $money 金额
 * @property int $num 地产币个数
 * @property int $time 操作时间
 */
class Bi extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'bi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'money', 'num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
