<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $name 名称
 * @property int $must 是否必选
 * @property int $time 创建时间
 */
class KeywordsType extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keywords_type';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'must', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'must' => 'integer', 'time' => 'integer'];
}
