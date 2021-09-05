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
 * @property int $id
 * @property int $uid 用户ID
 * @property string $lid 查看原图的列表
 * @property int $num 查看个数
 * @property int $time 时间
 */
class Userlooklingyuan extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userlooklingyuan';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'lid', 'num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
