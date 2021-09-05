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
 * @property string $name 目录名
 * @property int $time 操作时间
 * @property int $lists 排序
 */
class Mulu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mulu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'time', 'lists'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'time' => 'integer', 'lists' => 'integer'];
}
