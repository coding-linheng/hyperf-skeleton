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
 * @property int $parent_id 上级ID
 * @property string $name 名称
 * @property string $type 类型
 * @property int $lists 排序
 * @property string $url 链接
 */
class Wxmenu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wxmenu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'parent_id', 'name', 'type', 'lists', 'url'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'parent_id' => 'integer', 'lists' => 'integer'];
}
