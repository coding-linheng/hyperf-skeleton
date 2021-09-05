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
 * @property int $aid 专辑ID
 * @property int $scid 原创图片素材ID
 * @property string $path 路径
 * @property string $suffix 文件后缀名
 * @property int $size 文件大小
 * @property string $name 名称
 * @property int $shoucang 收藏数量
 * @property int $shoucolor 配色数量
 * @property int $colorfenlei 色彩分类
 * @property int $caiji 采集数量
 * @property int $share 原创求分享
 * @property int $sell 原创求售卖
 * @property int $cid 0自己上传的；其余的素材ID
 * @property string $title 标题
 * @property int $fenlei 分类
 * @property string $laiyuan 来源
 * @property int $jtui 作品精选
 * @property int $jtuitime 作品精选时间
 * @property int $tui 首页推荐
 * @property int $tuitime 首页推荐时间
 * @property int $time 时间
 * @property int $dtime 采集操作时间
 * @property int $colortime 配色收藏时间
 * @property int $yesterday 昨天最新
 * @property int $coloryesterday 配色昨天最新
 * @property int $lastweek 上周最新
 * @property int $colorlastweek 配色上周最新
 * @property int $del 1正常；2删除
 * @property int $yid 原来的ID
 * @property int $looknum 浏览量
 * @property int $downnum 下载图片数量
 * @property int $height 图片高度px
 * @property string $color 主颜色
 * @property int $r
 * @property int $g
 * @property int $b
 * @property int $count 占比数量
 * @property int $sum 总数量
 * @property int $percent 主颜色占比
 * @property int $is_color 是否有颜色
 * @property int $color_id 相同图片关联颜色ID
 * @property int $g_time 修改时间
 */
class Albumlist extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'albumlist';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'aid', 'scid', 'path', 'suffix', 'size', 'name', 'shoucang', 'shoucolor', 'colorfenlei', 'caiji', 'share', 'sell', 'cid', 'title', 'fenlei', 'laiyuan', 'jtui', 'jtuitime', 'tui', 'tuitime', 'time', 'dtime', 'colortime', 'yesterday', 'coloryesterday', 'lastweek', 'colorlastweek', 'del', 'yid', 'looknum', 'downnum', 'height', 'color', 'r', 'g', 'b', 'count', 'sum', 'percent', 'is_color', 'color_id', 'g_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'aid' => 'integer', 'scid' => 'integer', 'size' => 'integer', 'shoucang' => 'integer', 'shoucolor' => 'integer', 'colorfenlei' => 'integer', 'caiji' => 'integer', 'share' => 'integer', 'sell' => 'integer', 'cid' => 'integer', 'fenlei' => 'integer', 'jtui' => 'integer', 'jtuitime' => 'integer', 'tui' => 'integer', 'tuitime' => 'integer', 'time' => 'integer', 'dtime' => 'integer', 'colortime' => 'integer', 'yesterday' => 'integer', 'coloryesterday' => 'integer', 'lastweek' => 'integer', 'colorlastweek' => 'integer', 'del' => 'integer', 'yid' => 'integer', 'looknum' => 'integer', 'downnum' => 'integer', 'height' => 'integer', 'r' => 'integer', 'g' => 'integer', 'b' => 'integer', 'count' => 'integer', 'sum' => 'integer', 'percent' => 'integer', 'is_color' => 'integer', 'color_id' => 'integer', 'g_time' => 'integer'];
}
