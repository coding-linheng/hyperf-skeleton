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
 * @property int $uid 谁举报的
 * @property string $type 1灵感；2文库；2素材
 * @property int $bid 项目ID
 * @property string $text 举报内容
 * @property int $time 时间
 */
class Jubao extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'jubao';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'type', 'bid', 'text', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'bid' => 'integer', 'time' => 'integer'];
}
