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
 * @property string $title 标题
 * @property string $color 渐变颜色
 * @property int $img 图片
 * @property int $download 下载文件
 * @property int $lists 排序
 * @property int $time 时间
 */
class ColorChange extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'color_change';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'color', 'img', 'download', 'lists', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'img' => 'integer', 'download' => 'integer', 'lists' => 'integer', 'time' => 'integer'];
}
