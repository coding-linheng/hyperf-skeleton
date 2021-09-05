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
 * @property string $order_sn ordersn
 * @property string $money 支付金额
 * @property int $status 0未支付；1已支付
 * @property int $pay_time 支付时间
 * @property int $type 1支付宝；2微信
 * @property int $classify 1-4灵感；5-8文库；9-12素材；13地产币；17-20素材时间
 * @property int $num 增加的天数或者分数
 * @property int $time 创建订单时间
 */
class Order extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'order';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'order_sn', 'money', 'status', 'pay_time', 'type', 'classify', 'num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'status' => 'integer', 'pay_time' => 'integer', 'type' => 'integer', 'classify' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
