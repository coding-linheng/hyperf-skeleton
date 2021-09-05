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
 * @property int $uid 用户ID
 * @property int $lid 灵感ID
 */
class Shouling extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'shouling';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['uid', 'lid'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['uid' => 'integer', 'lid' => 'integer'];
}
