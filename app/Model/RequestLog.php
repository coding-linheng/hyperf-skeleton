<?php

declare(strict_types=1);

namespace App\Model;

/**
 * @property int $id
 * @property string $uid 用户ID
 * @property string $uri uri
 * @property string $ip uri
 * @property string $ip_location ip 地址位置
 * @property int $type 0 http请求 风控日志
 * @property string $refer 来源
 * @property string $user_agent 浏览器类型
 * @property string $request_params 专辑ID
 * @property string $request_method 请求方式，get,post..
 * @property int $time 时间
 */
class RequestLog extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'request_log';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'uri', 'ip', 'ip_location', 'type', 'refer', 'user_agent', 'request_params', 'request_method', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'type' => 'integer', 'time' => 'integer'];
}
