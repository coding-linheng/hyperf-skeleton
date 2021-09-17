<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $uid 用户ID
 * @property int $img_id 灵感图片Id
 * @property string $img_url 图片预览url
 * @property int $album_id 专辑Id
 * @property int $img_uid 图片所属的用户id
 * @property int $type 默认0是图片，1是专辑
 * @property int $c_time 收藏时间
 * @property string $remark 收藏备注，来源等
 */
class AlbumCollect extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album_collect';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'img_id', 'img_url', 'album_id', 'img_uid', 'type', 'c_time', 'remark'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'img_id' => 'integer', 'album_id' => 'integer', 'img_uid' => 'integer', 'type' => 'integer', 'c_time' => 'integer'];
}
