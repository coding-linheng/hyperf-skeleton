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
 * @property string $name 姓名
 * @property string $zhi 支付宝账号
 * @property string $money 金额
 * @property int $status 0待审核；1已通过；2已退回
 * @property int $dotime 操作时间
 * @property string $text 原因
 * @property int $time 申请时间
 */
class Tixian extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tixian';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'name', 'zhi', 'money', 'status', 'dotime', 'text', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'   => 'integer', 'uid' => 'integer', 'status' => 'integer', 'dotime' => 'integer',
        'time' => 'datetime:Y-m-d H:i:s',
    ];
}
