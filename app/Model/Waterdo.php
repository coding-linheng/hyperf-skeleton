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
 * @property int $uid 用户ID
 * @property int $doid 操作人ID
 * @property int $type 1关注专辑；2保存专辑；3关注我；4采集图片；5收藏文库；6收藏灵感；7收藏素材；8取消收藏素材；9取消收藏文库；10取消收藏灵感；11取消关注人
 * @property int $original 1非原创2原创
 * @property int $cid 采集图片ID
 * @property int $aid 专辑ID
 * @property int $status 1未看，2已看
 * @property int $zid 采集图片到哪个专辑
 * @property int $time 时间
 */
class Waterdo extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waterdo';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'doid', 'type', 'original', 'cid', 'aid', 'status', 'zid', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'doid' => 'integer', 'type' => 'integer', 'original' => 'integer', 'cid' => 'integer', 'aid' => 'integer', 'status' => 'integer', 'zid' => 'integer', 'time' => 'datetime:Y-m-d H:i:s'];
}
