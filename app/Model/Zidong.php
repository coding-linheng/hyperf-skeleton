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
 * @property string $title 用户发的文字
 * @property int $type 0文字；1图片
 * @property string $media_id
 * @property int $img
 * @property string $txt 回复文字
 * @property int $time 时间
 */
class Zidong extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'zidong';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'type', 'media_id', 'img', 'txt', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'img' => 'integer', 'time' => 'integer'];
}
