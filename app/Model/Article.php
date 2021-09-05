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
 * @property int $id 文章ID
 * @property int $member_id 会员id
 * @property string $name 文章名称
 * @property int $category_id 文章分类
 * @property string $describe 描述
 * @property string $content 文章内容
 * @property int $cover_id 封面图片id
 * @property int $file_id 文件id
 * @property string $img_ids
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 * @property int $status 数据状态
 */
class Article extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'article';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'member_id', 'name', 'category_id', 'describe', 'content', 'cover_id', 'file_id', 'img_ids', 'create_time', 'update_time', 'status'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'member_id' => 'integer', 'category_id' => 'integer', 'cover_id' => 'integer', 'file_id' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer', 'status' => 'integer'];
}
