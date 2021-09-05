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
 * @property int $img1 图片1
 * @property string $url1 链接1
 * @property int $img2 图片2
 * @property string $url2 链接2
 * @property int $img3
 * @property string $url3
 * @property int $img4
 * @property string $url4
 * @property int $img5
 * @property string $url5
 * @property int $img6
 * @property string $url6
 * @property int $img7
 * @property string $url7
 * @property int $img8
 * @property string $url8
 * @property int $img9
 * @property string $url9
 * @property int $img10
 * @property string $url10
 * @property int $time
 */
class Top extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'top';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'img1', 'url1', 'img2', 'url2', 'img3', 'url3', 'img4', 'url4', 'img5', 'url5', 'img6', 'url6', 'img7', 'url7', 'img8', 'url8', 'img9', 'url9', 'img10', 'url10', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'img1' => 'integer', 'img2' => 'integer', 'img3' => 'integer', 'img4' => 'integer', 'img5' => 'integer', 'img6' => 'integer', 'img7' => 'integer', 'img8' => 'integer', 'img9' => 'integer', 'img10' => 'integer', 'time' => 'integer'];
}
