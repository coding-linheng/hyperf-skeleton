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
 * @property int $cid 素材ID
 * @property int $uid 用户ID
 * @property int $num 采集的次数
 */
class Caiji extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'caiji';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['cid', 'uid', 'num'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['cid' => 'integer', 'uid' => 'integer', 'num' => 'integer'];
}
