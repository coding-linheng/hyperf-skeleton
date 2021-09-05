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
 * @property int $uid 用户消息
 * @property string $title 标题
 * @property string $des 描述
 * @property int $status 1未看；2已看
 * @property int $time 时间
 */
class Say extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'say';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'title', 'des', 'status', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'status' => 'integer', 'time' => 'integer'];
}
