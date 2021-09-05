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
 * @property string $lip 查看的灵感图片
 * @property int $num 查看数量
 * @property int $time 当天的时间
 */
class Userlookling extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userlookling';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'lip', 'num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
