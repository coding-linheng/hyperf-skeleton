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
 * @property string $name 标题
 * @property int $lists 越小越往前
 * @property int $img 图片
 * @property int $time 时间
 */
class Lingfenlei extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lingfenlei';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'lists', 'img', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'lists' => 'integer', 'img' => 'integer', 'time' => 'integer'];
}
