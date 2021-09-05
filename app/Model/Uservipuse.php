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
 * @property int $uid
 * @property int $type 1灵感；2文库
 * @property int $paytime 第一次支付的时间
 * @property int $vip1num vip1天数
 * @property int $vip2num vip2天数
 * @property int $vip3num vip3天数
 * @property int $vip4num
 */
class Uservipuse extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uservipuse';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'type', 'paytime', 'vip1num', 'vip2num', 'vip3num', 'vip4num'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer', 'type' => 'integer', 'paytime' => 'integer', 'vip1num' => 'integer', 'vip2num' => 'integer', 'vip3num' => 'integer', 'vip4num' => 'integer'];
}
