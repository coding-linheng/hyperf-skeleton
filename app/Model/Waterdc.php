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
 * @property int $uid
 * @property string $score 地产币
 * @property int $type 1审核通过增加积分；2签到；3被下载增加积分;4下载减少的地产币
 * @property int $status 1素材；2文库
 * @property int $wid 文库或者素材ID
 * @property int $bid 关联ID
 * @property string $name 关联名称
 * @property int $is_vip 0正常；1vip下载
 * @property int $time
 */
class Waterdc extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waterdc';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'score', 'type', 'status', 'wid', 'bid', 'name', 'is_vip', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'type' => 'integer', 'status' => 'integer', 'wid' => 'integer', 'bid' => 'integer', 'is_vip' => 'integer', 'time' => 'integer'];
}
