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
 * @property int $type 1灵感；2文库；3素材共享时间；4素材地产币时间；5免费文库时间；6原创文库时间
 * @property int $vip vip等级
 * @property int $paytime 支付时间
 * @property int $vip1num
 * @property int $vip2num
 * @property int $vip3num
 * @property int $vip4num
 * @property int $vip5num
 * @property int $vip6num
 * @property int $vip7num
 * @property int $vip8num
 * @property int $vip9num
 * @property int $vip10num
 * @property int $vip11num
 * @property int $vip12num
 * @property int $vip13num
 * @property int $time vip到期时间
 */
class Uservip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'uservip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'type', 'vip', 'paytime', 'vip1num', 'vip2num', 'vip3num', 'vip4num', 'vip5num', 'vip6num', 'vip7num', 'vip8num', 'vip9num', 'vip10num', 'vip11num', 'vip12num', 'vip13num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer', 'type' => 'integer', 'vip' => 'integer', 'paytime' => 'integer', 'vip1num' => 'integer', 'vip2num' => 'integer', 'vip3num' => 'integer', 'vip4num' => 'integer', 'vip5num' => 'integer', 'vip6num' => 'integer', 'vip7num' => 'integer', 'vip8num' => 'integer', 'vip9num' => 'integer', 'vip10num' => 'integer', 'vip11num' => 'integer', 'vip12num' => 'integer', 'vip13num' => 'integer', 'time' => 'integer'];
}
