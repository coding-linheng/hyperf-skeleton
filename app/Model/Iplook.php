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
 * @property string $ip 用户IP
 * @property string $lip 灵感IP
 * @property int $num 查看数量
 * @property int $time 当天时间
 */
class Iplook extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'iplook';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'ip', 'lip', 'num', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'num' => 'integer', 'time' => 'integer'];
}
