<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */
namespace App\Model;

/**
 * @property int $id
 * @property int $uid 用户ID
 * @property string $avatar 头像
 * @property string $name 真实姓名
 * @property string $tui 推荐人
 * @property string $tui_name 推荐人个数（每天归零）
 * @property int $tuitime 推荐注册时间
 * @property int $viptime vip开通时间
 * @property int $chargetime 充值原创币时间
 * @property string $total 总收入
 * @property int $status 0未提交；1已提交；2已通过；3未通过
 * @property int $time 操作时间
 */
class InviteProfit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'invite_profit';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'uid', 'avatar', 'name', 'tui', 'tui_name', 'tuitime', 'viptime', 'chargetime', 'total', 'status', 'time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'uid' => 'integer', 'tuitime' => 'integer', 'viptime' => 'integer', 'chargetime' => 'integer', 'status' => 'integer', 'time' => 'integer'];
}
