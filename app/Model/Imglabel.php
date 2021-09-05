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
 * @property int $aid 专辑ID
 * @property string $name 标签
 */
class Imglabel extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'imglabel';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['aid', 'name'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['aid' => 'integer'];
}
