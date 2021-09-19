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
 * @property int $uid 用户ID
 * @property string $name 真实姓名
 * @property string $tel 手机号
 * @property string $cardnum 身份证号
 * @property string $zhi 支付宝
 * @property string $qq QQ
 * @property string $email 邮箱
 * @property string $cardimg 身份证照片
 * @property string $cardimg1 反面
 * @property int $status 0未提交；1已提交；2已通过；3未通过
 * @property string $text 不通过原因
 * @property int $shoucang 收藏数量
 * @property int $shoucolor 收藏配色
 * @property int $shouling 收藏灵感
 * @property int $shouwen 收藏文库
 * @property int $shousucai 收藏素材
 * @property int $zhuanji 专辑数量
 * @property int $zuopin 作品数量
 * @property int $sucainum 素材数量
 * @property int $wenkunum 文库数量
 * @property int $tui 推荐人个数（每天归零）
 * @property int $tuitime 推荐时间
 * @property string $total 总收入
 * @property int $time 操作时间
 */
class Userdata extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'userdata';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1', 'status', 'text', 'shoucang', 'shoucolor', 'shouling', 'shouwen', 'shousucai', 'zhuanji', 'zuopin', 'sucainum', 'wenkunum', 'tui', 'tuitime', 'total', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'status' => 'integer', 'shoucang' => 'integer', 'shoucolor' => 'integer', 'shouling' => 'integer', 'shouwen' => 'integer', 'shousucai' => 'integer', 'zhuanji' => 'integer', 'zuopin' => 'integer', 'sucainum' => 'integer', 'wenkunum' => 'integer', 'tui' => 'integer', 'tuitime' => 'integer', 'time' => 'integer'];
}
