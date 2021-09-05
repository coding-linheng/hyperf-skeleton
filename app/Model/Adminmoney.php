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
 * @property string $score 共享分
 * @property string $dc 地产币
 * @property int $time 操作时间
 */
class Adminmoney extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'adminmoney';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'score', 'dc', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'time' => 'integer'];
}
