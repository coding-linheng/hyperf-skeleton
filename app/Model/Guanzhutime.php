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
 * @property int $uid 用户ID
 * @property int $aid 灵感ID
 * @property int $time 关注时间
 */
class Guanzhutime extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'guanzhutime';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'aid', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer', 'aid' => 'integer', 'time' => 'integer'];
}
