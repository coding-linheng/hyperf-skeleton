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
 * @property int $mid 品牌名称ID
 * @property int $iid 专辑ID
 */
class BrandNameIrelation extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'brand_name_irelation';

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
