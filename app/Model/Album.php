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
 * @property int $unnum 唯一编号
 * @property int $uid 用户ID
 * @property string $name 专辑名称
 * @property int $fenlei 专辑分类
 * @property int $brandscenes 品牌行业分类
 * @property int $brandname 品牌名称分类
 * @property int $branduse 品牌用途分类
 * @property int $paintcountry 绘画国家分类
 * @property int $paintname 绘画名字分类
 * @property int $paintstyle 绘画风格分类
 * @property int $status 1未传图片；2已传图片
 * @property int $num 传图的张数
 * @property int $baocun 保存数
 * @property int $guanzhu 关注数量
 * @property int $jing 0未设置；2已设置
 * @property int $looknum 浏览次数
 * @property int $fid 封面ID
 * @property string $fengmian 封面
 * @property int $tui 2首页推荐
 * @property int $tuitime 精品推荐时间
 * @property int $yesterday 昨天热门
 * @property int $ltui 2灵感推荐
 * @property int $isoriginal 是否原创 1否2是
 * @property int $isopensale 是否开放售卖1不开放2开放
 * @property int $t_time 推荐时间
 * @property int $week 那一周
 * @property int $weekguanzhu 第几周的关注量
 * @property int $daynum 今日关注量
 * @property int $daytime 哪一天
 * @property string $del 1正常；2删除
 * @property int $time 操作时间
 * @property int $yid 原ID
 * @property int $g_time 关注时间
 */
class Album extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'album';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'unnum', 'uid', 'name', 'fenlei', 'brandscenes', 'brandname', 'branduse', 'paintcountry', 'paintname', 'paintstyle', 'status', 'num', 'baocun', 'guanzhu', 'jing', 'looknum', 'fid', 'fengmian', 'tui', 'tuitime', 'yesterday', 'ltui', 'isoriginal', 'isopensale', 't_time', 'week', 'weekguanzhu', 'daynum', 'daytime', 'del', 'time', 'yid', 'g_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'unnum' => 'integer', 'uid' => 'integer', 'fenlei' => 'integer', 'brandscenes' => 'integer', 'brandname' => 'integer', 'branduse' => 'integer', 'paintcountry' => 'integer', 'paintname' => 'integer', 'paintstyle' => 'integer', 'status' => 'integer', 'num' => 'integer', 'baocun' => 'integer', 'guanzhu' => 'integer', 'jing' => 'integer', 'looknum' => 'integer', 'fid' => 'integer', 'tui' => 'integer', 'tuitime' => 'integer', 'yesterday' => 'integer', 'ltui' => 'integer', 'isoriginal' => 'integer', 'isopensale' => 'integer', 't_time' => 'integer', 'week' => 'integer', 'weekguanzhu' => 'integer', 'daynum' => 'integer', 'daytime' => 'integer', 'time' => 'integer', 'yid' => 'integer', 'g_time' => 'integer'];
}
