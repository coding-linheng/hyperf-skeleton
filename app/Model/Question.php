<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property int $type 类型id
 * @property string $title 标题
 * @property string $content 内容
 * @property string $contact 联系方式
 * @property int $create_time 创建时间
 */
class Question extends Model
{
    public const CREATED_AT = 'create_time';

    public const UPDATED_AT = null;

    public $timestamps = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'question';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'type', 'title', 'content', 'contact', 'create_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'create_time' => 'datetime:Y-m-d H:i:s'];
}
