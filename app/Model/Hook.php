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
 * @property int $id 主键
 * @property string $name 钩子名称
 * @property string $describe 描述
 * @property string $addon_list 钩子挂载的插件 '，'分割
 * @property int $status
 * @property int $update_time 更新时间
 * @property int $create_time 创建时间
 */
class Hook extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'hook';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'describe', 'addon_list', 'status', 'update_time', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'update_time' => 'integer', 'create_time' => 'integer'];
}
