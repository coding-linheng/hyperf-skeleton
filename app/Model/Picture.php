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
 * @property int $id 主键id自增
 * @property string $name 图片名称
 * @property string $path 路径
 * @property string $url 图片链接
 * @property string $sha1 文件 sha1编码
 * @property int $create_time 创建时间
 * @property int $update_time
 * @property int $status 状态
 */
class Picture extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'picture';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'path', 'url', 'sha1', 'create_time', 'update_time', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer', 'status' => 'integer'];
}
