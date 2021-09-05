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
 * @property string $ordinary 普通
 * @property string $super 超级
 * @property string $platinum 黄金
 * @property string $diamonds 钻石
 * @property string $title 描述
 */
class Vip extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'vip';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'ordinary', 'super', 'platinum', 'diamonds', 'title'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer'];
}
