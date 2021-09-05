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
 * @property string $name 插件名或标识
 * @property string $title 中文名称
 * @property string $describe 插件描述
 * @property string $config 配置
 * @property string $author 作者
 * @property string $version 版本号
 * @property int $status 状态
 * @property int $create_time 安装时间
 * @property int $update_time
 */
class Addon extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'addon';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'title', 'describe', 'config', 'author', 'version', 'status', 'create_time', 'update_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer'];
}
