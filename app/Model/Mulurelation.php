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
 * @property int $mid 目录ID
 * @property int $iid 素材ID
 */
class Mulurelation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'mulurelation';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['mid', 'iid'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['mid' => 'integer', 'iid' => 'integer'];
}
