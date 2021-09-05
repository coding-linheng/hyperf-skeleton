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
 * @property string $path 图片路径
 * @property string $suffix 文件后缀名
 * @property int $size 文件字节数
 * @property string $name 文件名
 * @property int $status 0待处理；1审核中；2未通过；3已通过
 * @property int $del 0正常；1删除
 * @property string $img 预览图ID
 * @property string $title 标题
 * @property int $leixing 1共享素材；2原创素材
 * @property string $price 价格
 * @property int $ttime 通过时间
 * @property int $dtime 下载操作时间
 * @property string $text 不通过原因
 * @property int $shoucang 收藏量
 * @property int $downnum 下载次数
 * @property int $tui 2推荐
 * @property int $t_time 推荐时间
 * @property int $time 时间
 * @property int $week 周数
 * @property int $weekguanzhu 下载数
 * @property int $looknum 浏览量
 * @property int $height 高度
 * @property int $yesterday 昨日热门
 * @property int $ll
 * @property string $guanjianci 关键词
 * @property int $g_time 修改时间
 */
class Img extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'img';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'unnum', 'uid', 'path', 'suffix', 'size', 'name', 'status', 'del', 'img', 'title', 'leixing', 'price', 'ttime', 'dtime', 'text', 'shoucang', 'downnum', 'tui', 't_time', 'time', 'week', 'weekguanzhu', 'looknum', 'height', 'yesterday', 'll', 'guanjianci', 'g_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'unnum' => 'integer', 'uid' => 'integer', 'size' => 'integer', 'status' => 'integer', 'del' => 'integer', 'leixing' => 'integer', 'ttime' => 'integer', 'dtime' => 'integer', 'shoucang' => 'integer', 'downnum' => 'integer', 'tui' => 'integer', 't_time' => 'integer', 'time' => 'integer', 'week' => 'integer', 'weekguanzhu' => 'integer', 'looknum' => 'integer', 'height' => 'integer', 'yesterday' => 'integer', 'll' => 'integer', 'g_time' => 'integer'];
}
