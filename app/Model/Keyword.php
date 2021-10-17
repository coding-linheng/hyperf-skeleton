<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $type 类型
 * @property string $name 名称
 * @property int $create_time 创建时间
 * @property int $update_time 修改时间
 */
class Keyword extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'keywords';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'type', 'name', 'create_time', 'update_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer'];
}
