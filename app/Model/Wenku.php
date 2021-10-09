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
 * @property int $unnum 唯一编号
 * @property int $uid 用户ID
 * @property string $path 图片路径
 * @property string $suffix 文件后缀名
 * @property int $size 文件字节数
 * @property string $geshi 文件格式
 * @property string $name 文件名
 * @property int $status 0待处理；1审核中；2未通过；3已通过
 * @property int $del 0正常；1删除
 * @property string $img 预览图ID
 * @property string $title 标题
 * @property string $des 描述
 * @property int $leixing 1共享素材；2原创素材
 * @property string $price 价格
 * @property int $ttime 修改时间
 * @property int $dtime 下载时间
 * @property int $yesterday 昨日最新
 * @property string $text 原因
 * @property int $shoucang 收藏数量
 * @property int $downnum 下载次数
 * @property int $tui 2推荐
 * @property int $t_time 推荐时间
 * @property string $pdf 本地的pdf文件
 * @property string $pdfimg 第一页pdf
 * @property int $pagenum 页数
 * @property int $step 0未处理；1已下载；
 * @property string $pdfpath 远程pdfpath
 * @property int $time 时间
 * @property int $week 周数
 * @property int $weekguanzhu 本周关注
 * @property int $looknum 浏览量
 * @property int $num 页数
 * @property int $g_time 操作时间
 */
class Wenku extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'wenku';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'unnum', 'uid', 'path', 'suffix', 'size', 'geshi', 'name', 'status', 'del', 'img', 'title', 'des', 'leixing', 'price', 'ttime', 'dtime', 'yesterday', 'text', 'shoucang', 'downnum', 'tui', 't_time', 'pdf', 'pdfimg', 'pagenum', 'step', 'pdfpath', 'time', 'week', 'weekguanzhu', 'looknum', 'num', 'g_time'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'unnum' => 'integer', 'uid' => 'integer', 'size' => 'integer', 'status' => 'integer', 'del' => 'integer', 'leixing' => 'integer', 'ttime' => 'integer', 'dtime' => 'integer', 'yesterday' => 'integer', 'shoucang' => 'integer', 'downnum' => 'integer', 'tui' => 'integer', 't_time' => 'integer', 'pagenum' => 'integer', 'step' => 'integer', 'time' => 'datetime:Y-m-d H:i:s', 'week' => 'integer', 'weekguanzhu' => 'integer', 'looknum' => 'integer', 'num' => 'integer', 'g_time' => 'integer'];
}
