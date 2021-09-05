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
 * @property int $bid 谁的东西
 * @property int $wid 文库ID
 * @property int $type 1文库；2素材
 * @property int $uid 谁下载的
 * @property string $score 下载使用的积分
 * @property string $dc 花的地产币
 * @property int $vip 0正常下载；1vip下载
 * @property int $time 操作时间
 */
class Waterdown extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'waterdown';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'bid', 'wid', 'type', 'uid', 'score', 'dc', 'vip', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'bid' => 'integer', 'wid' => 'integer', 'type' => 'integer', 'uid' => 'integer', 'vip' => 'integer', 'time' => 'integer'];
}
