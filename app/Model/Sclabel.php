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
 * @property string $title 标签
 * @property int $fid 分类id
 * @property int $time 时间
 */
class Sclabel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'sclabel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'fid', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'fid' => 'integer', 'time' => 'integer'];
}
