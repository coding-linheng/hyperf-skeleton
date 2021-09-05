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
 * @property string $name 链接名称
 * @property int $img_id 链接图片封面
 * @property string $url 链接地址
 * @property string $describe 描述
 * @property int $sort 排序
 * @property int $status 数据状态
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class Blogroll extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'blogroll';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'img_id', 'url', 'describe', 'sort', 'status', 'create_time', 'update_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'img_id' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer'];
}
