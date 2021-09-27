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
 * @property int $uid
 * @property string $score 共享分
 * @property int $type 1审核通过增加积分；2签到；3被下载增加积分；4下载扣除的积分
 * @property int $status 1素材；2文库
 * @property int $wid 文库或者素材ID
 * @property int $bid 关联ID
 * @property string $name 名称
 * @property int $time
 */
class Waterscore extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waterscore';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'score', 'type', 'status', 'wid', 'bid', 'name', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'  => 'integer', 'uid' => 'integer', 'type' => 'integer', 'status' => 'integer',
        'wid' => 'integer', 'bid' => 'integer', 'time' => 'datetime:Y-m-d H:i:s',
    ];
}
