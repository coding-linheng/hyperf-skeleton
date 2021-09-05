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
 * @property int $uid 用户ID
 * @property string $title 标题
 * @property int $fid 分类ID
 * @property string $ids 图片id集
 * @property string $des 合集描述
 * @property int $collection 收藏数量
 * @property int $num 总数量
 * @property string $lebel 标签合集
 * @property int $is_tui 0正常；1推荐
 * @property int $is_shou 0自己创建的；1收藏的
 * @property int $sid 被收藏的ID
 * @property int $datetui 推荐月份
 * @property int $updatetime 修改时间
 * @property int $time 创建时间
 */
class Heji extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'heji';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'title', 'fid', 'ids', 'des', 'collection', 'num', 'lebel', 'is_tui', 'is_shou', 'sid', 'datetui', 'updatetime', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'fid' => 'integer', 'collection' => 'integer', 'num' => 'integer', 'is_tui' => 'integer', 'is_shou' => 'integer', 'sid' => 'integer', 'datetui' => 'integer', 'updatetime' => 'integer', 'time' => 'integer'];
}
