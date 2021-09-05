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
 * @property int $id 主键
 * @property string $service_name 服务标识
 * @property string $driver_name 驱动标识
 * @property string $config 配置
 * @property int $status 状态
 * @property int $create_time 安装时间
 * @property int $update_time
 */
class Driver extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'driver';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'service_name', 'driver_name', 'config', 'status', 'create_time', 'update_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'create_time' => 'integer', 'update_time' => 'integer'];
}
