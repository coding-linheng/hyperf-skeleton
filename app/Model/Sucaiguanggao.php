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
 * @property string $title 标题
 * @property int $img 图片
 * @property string $url 链接
 * @property int $time 时间
 */
class Sucaiguanggao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sucaiguanggao';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'img', 'url', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'img' => 'integer', 'time' => 'integer'];
}
