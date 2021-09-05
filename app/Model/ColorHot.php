<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model;

/**
 * @property int $id
 * @property int $pid 分类ID
 * @property string $title 颜色名称
 * @property string $color 色值
 * @property int $lists 排序
 * @property int $time 时间
 */
class ColorHot extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'color_hot';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'title', 'color', 'lists', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'lists' => 'integer', 'time' => 'integer'];
}
