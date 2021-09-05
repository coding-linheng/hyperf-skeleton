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
 * @property int $id 分类ID
 * @property string $name 分类名称
 * @property string $describe 描述
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $status 数据状态
 * @property string $icon 分类图标
 */
class ArticleCategory extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article_category';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'describe', 'create_time', 'update_time', 'status', 'icon'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer', 'status' => 'integer'];
}
