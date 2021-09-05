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
 * @property int $mid 国家ID
 * @property string $name 标题
 * @property int $lists 排序
 * @property int $time 时间
 */
class PaintName extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'paint_name';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'mid', 'name', 'lists', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'mid' => 'integer', 'lists' => 'integer', 'time' => 'integer'];
}
