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
 * @property string $name 接口名称
 * @property int $group_id 接口分组
 * @property int $request_type 请求类型 0:POST  1:GET
 * @property string $api_url 请求路径
 * @property string $describe 接口描述
 * @property string $describe_text 接口富文本描述
 * @property int $is_request_data 是否需要请求数据
 * @property string $request_data 请求数据
 * @property string $response_data 响应数据
 * @property int $is_response_data 是否需要响应数据
 * @property int $is_user_token 是否需要用户token
 * @property int $is_response_sign 是否返回数据签名
 * @property int $is_request_sign 是否验证请求数据签名
 * @property string $response_examples 响应栗子
 * @property int $developer 研发者
 * @property int $api_status 接口状态（0:待研发，1:研发中，2:测试中，3:已完成）
 * @property int $is_page 是否为分页接口 0：否  1：是
 * @property int $sort 排序
 * @property int $status 数据状态
 * @property int $create_time 创建时间
 * @property int $update_time 更新时间
 */
class Api extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'name', 'group_id', 'request_type', 'api_url', 'describe', 'describe_text', 'is_request_data', 'request_data', 'response_data', 'is_response_data', 'is_user_token', 'is_response_sign', 'is_request_sign', 'response_examples', 'developer', 'api_status', 'is_page', 'sort', 'status', 'create_time', 'update_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'group_id' => 'integer', 'request_type' => 'integer', 'is_request_data' => 'integer', 'is_response_data' => 'integer', 'is_user_token' => 'integer', 'is_response_sign' => 'integer', 'is_request_sign' => 'integer', 'developer' => 'integer', 'api_status' => 'integer', 'is_page' => 'integer', 'sort' => 'integer', 'status' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer'];
}
