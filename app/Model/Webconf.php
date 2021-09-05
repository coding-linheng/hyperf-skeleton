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
 * @property string $title 标题
 * @property string $des 描述
 * @property string $wx 微信
 * @property string $qq qq号
 * @property int $qrcode 公众号二维码
 * @property int $webqrcode 网站二维码
 * @property int $qun 群二维码
 * @property int $logo logo
 * @property int $buttonlogo 底部logo
 * @property string $banquan 版权
 * @property int $time
 */
class Webconf extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'webconf';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'title', 'des', 'wx', 'qq', 'qrcode', 'webqrcode', 'qun', 'logo', 'buttonlogo', 'banquan', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'qrcode' => 'integer', 'webqrcode' => 'integer', 'qun' => 'integer', 'logo' => 'integer', 'buttonlogo' => 'integer', 'time' => 'integer'];
}
