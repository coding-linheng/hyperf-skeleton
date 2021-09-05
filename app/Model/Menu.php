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
 * @property int $id 文档ID
 * @property string $name 菜单名称
 * @property int $pid 上级分类ID
 * @property int $sort 排序（同级有效）
 * @property int $img 图片
 * @property string $module 模块
 * @property string $url 链接地址
 * @property int $is_hide 是否隐藏
 * @property int $is_shortcut 是否快捷操作
 * @property string $icon 图标
 * @property int $status 状态
 * @property int $update_time
 * @property int $create_time
 */
class Menu extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'menu';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'pid', 'sort', 'img', 'module', 'url', 'is_hide', 'is_shortcut', 'icon', 'status', 'update_time', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'sort' => 'integer', 'img' => 'integer', 'is_hide' => 'integer', 'is_shortcut' => 'integer', 'status' => 'integer', 'update_time' => 'integer', 'create_time' => 'integer'];
}
