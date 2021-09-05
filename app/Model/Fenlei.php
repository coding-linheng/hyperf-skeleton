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
 * @property int $mid 素材目录
 * @property string $name 目录名
 * @property int $img 图片
 * @property int $lists 排序
 * @property int $time 操作时间
 */
class Fenlei extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'fenlei';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'mid', 'name', 'img', 'lists', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'mid' => 'integer', 'img' => 'integer', 'lists' => 'integer', 'time' => 'integer'];
}
