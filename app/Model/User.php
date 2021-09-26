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

use Hyperf\Scout\Searchable;
use Qbhy\HyperfAuth\Authenticatable;

/**
 * @property int $id
 * @property string $openid 用户openid
 * @property string $unionid unionid
 * @property int $parent_id 上级ID
 * @property string $username 用户名
 * @property string $nickname 用户昵称
 * @property string $imghead 用户头像
 * @property string $email 邮箱
 * @property int $sex 1男;2女；
 * @property int $subscribe 微信关注状态
 * @property int $usertype 1微信2小程序
 * @property string $password 密码
 * @property string $address 地址
 * @property string $content 简介
 * @property int $vip 0没有支付过的，1支付过灵感；2支付过文库；3两个都支付过
 * @property int $score 共享分
 * @property string $dc 地产币
 * @property string $money 余额
 * @property int $sheng 省
 * @property int $city 城市
 * @property int $qu 区
 * @property int $qi 人气
 * @property int $fans 粉丝数
 * @property int $guan 关注
 * @property int $isarr 工作组权限
 * @property int $lvip
 * @property int $isview 查看张数
 * @property int $isbrand 品牌馆权限 1否2是
 * @property int $ispaint 绘画馆权限 1否2是
 * @property int $iszj 普通专辑 是否禁止 1否2是
 * @property int $isyczj 原创专辑 是否禁止 1否2是
 * @property string $qq QQ号
 * @property string $wx 微信
 * @property int $time 加入时间
 * @property int $logintime 最后登录时间
 * @property string $jinzhi 1正常；2禁止登陆
 * @property int $get_score 每次签到给10个积分
 * @property int $scoretime 积分到期时间
 * @property string $invitecode 邀请者
 * @property string $mobile 邀请者
 */
class User extends Model implements Authenticatable
{
    use Searchable;

    public int $contentId;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'openid', 'unionid', 'parent_id', 'username', 'nickname', 'imghead', 'email', 'sex', 'subscribe',
        'usertype', 'password', 'address', 'content', 'vip', 'score', 'dc', 'money', 'sheng', 'city', 'qu', 'qi',
        'fans', 'guan', 'isarr', 'lvip', 'isview', 'isbrand', 'ispaint', 'iszj', 'isyczj', 'qq', 'wx', 'time',
        'logintime', 'jinzhi', 'get_score', 'scoretime', 'invitecode',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id'        => 'integer', 'parent_id' => 'integer', 'sex' => 'integer', 'subscribe' => 'integer',
        'usertype'  => 'integer', 'vip' => 'integer', 'score' => 'integer', 'sheng' => 'integer', 'city' => 'integer',
        'qu'        => 'integer', 'qi' => 'integer', 'fans' => 'integer', 'guan' => 'integer', 'isarr' => 'integer',
        'lvip'      => 'integer', 'isview' => 'integer', 'isbrand' => 'integer', 'ispaint' => 'integer',
        'iszj'      => 'integer', 'isyczj' => 'integer', 'time' => 'integer', 'logintime' => 'integer',
        'get_score' => 'integer', 'scoretime' => 'integer',
    ];

    public function getId()
    {
        return $this->contentId;
    }

    public static function retrieveById($key): ?Authenticatable
    {
        /** @var User $user */
        $user = User::query()->where('id', $key)->first();
        return $user ?? null;
    }
}
