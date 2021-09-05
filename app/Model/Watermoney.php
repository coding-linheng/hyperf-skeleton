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
 * @property int $paytype 1微信充值；2支付宝；3邀请两个好友
 * @property string $money 金额
 * @property int $type 1-4灵感；5-8文库；9-12素材；13地产币；14邀请好友;17-20素材时间;21-23文库时间；24-26文库时间
 * @property int $day
 * @property string $score 分数
 * @property int $daiqitime 到期时间
 * @property int $time 操作时间
 */
class Watermoney extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'watermoney';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'paytype', 'money', 'type', 'day', 'score', 'daiqitime', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'paytype' => 'integer', 'type' => 'integer', 'day' => 'integer', 'daiqitime' => 'integer', 'time' => 'integer'];
}
