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
 * @property string $title
 * @property int $lists 排序
 * @property int $time 时间
 */
class ColorFenlei extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'color_fenlei';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'lists', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'lists' => 'integer', 'time' => 'integer'];
}
