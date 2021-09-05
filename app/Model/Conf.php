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
 * @property int $status 1关于我们；2用户协议；3版权声明；4支付协议；5出售协议；6我要供稿；7上传奖励；8联系我们；9广告合作
 * @property string $title 标题
 * @property string $content 内容
 * @property int $time 操作时间
 */
class Conf extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'conf';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'status', 'title', 'content', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'status' => 'integer', 'time' => 'integer'];
}
