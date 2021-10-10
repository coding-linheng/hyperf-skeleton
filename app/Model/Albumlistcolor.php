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

use Hyperf\Scout\Searchable;
/**
 * @property int $id
 * @property int $pid 图片ID
 * @property string $color 颜色
 * @property int $r
 * @property int $g
 * @property int $b
 * @property int $count 占比数量
 * @property int $percent 百分比
 * @property int $num 排行
 */
class Albumlistcolor extends Model
{
    use Searchable;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'albumlistcolor';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'pid', 'color', 'r', 'g', 'b', 'count', 'percent', 'num'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'pid' => 'integer', 'r' => 'integer', 'g' => 'integer', 'b' => 'integer', 'count' => 'integer', 'percent' => 'integer', 'num' => 'integer'];
}
