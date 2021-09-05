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
 * @property int $id 配置ID
 * @property string $name 配置名称
 * @property int $type 配置类型
 * @property string $title 配置标题
 * @property int $group 配置分组
 * @property string $extra 配置选项
 * @property string $describe 配置说明
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $status 状态
 * @property string $value 配置值
 * @property int $sort 排序
 */
class Config extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'config';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'type', 'title', 'group', 'extra', 'describe', 'create_time', 'update_time', 'status', 'value', 'sort'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'group' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer', 'status' => 'integer', 'sort' => 'integer'];
}
