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
    use Searchable;

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

    // protected $indexConfigurator = MyIndexConfigurator::class;

    protected $searchRules = [
    ];

    // Here you can specify a mapping for model fields
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                // Also you can configure multi-fields, more details you can find here https://www.elastic.co/guide/en/elasticsearch/reference/current/multi-fields.html
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ],
                ],
            ],
        ],
    ];

    /**
     * 获取用户的姓名.
     *
     * @param string $value
     * @return string
     */
    public function getPathAttribute($value)
    {
        return env('PUBLIC_DOMAIN') . '/' . $value;
    }
}

//搜索具体栏位
//{"query":{"bool":{"must":[],"must_not":[],"should":[{"query_string":{"default_field":"title","query":"春节吃到嗨"}},{"query_string":{"default_field":"label","query":""}}]}},"from":0,"size":250,"sort":[],"aggs":{}}
//{"query":{"bool":{"must":[],"must_not":[],"should":[{"query_string":{"default_field":"name","query":"春分节气"}},{"query_string":{"default_field":"title","query":"春分节气"}}]}},"from":0,"size":10,"sort":[],"aggs":{}}

//创建索引带分词
// put http://119.23.59.3:9200/dc
//{
//    "mappings": {
//    "dc10000albumlist": {
//        "properties": {
//            "aid": {
//                "type": "long"
//				},
//				"b": {
//                "type": "long"
//				},
//				"caiji": {
//                "type": "long"
//				},
//				"cid": {
//                "type": "long"
//				},
//				"color": {
//                "type": "string"
//				},
//				"color_id": {
//                "type": "long"
//				},
//				"colorfenlei": {
//                "type": "long"
//				},
//				"colorlastweek": {
//                "type": "long"
//				},
//				"colortime": {
//                "type": "long"
//				},
//				"coloryesterday": {
//                "type": "long"
//				},
//				"count": {
//                "type": "long"
//				},
//				"del": {
//                "type": "long"
//				},
//				"downnum": {
//                "type": "long"
//				},
//				"dtime": {
//                "type": "long"
//				},
//				"fenlei": {
//                "type": "long"
//				},
//				"g": {
//                "type": "long"
//				},
//				"g_time": {
//                "type": "long"
//				},
//				"height": {
//                "type": "long"
//				},
//				"id": {
//                "type": "long"
//				},
//				"is_color": {
//                "type": "long"
//				},
//				"jtui": {
//                "type": "long"
//				},
//				"jtuitime": {
//                "type": "long"
//				},
//				"laiyuan": {
//                "type": "string"
//				},
//				"lastweek": {
//                "type": "long"
//				},
//				"looknum": {
//                "type": "long"
//				},
//				"name": {
//                "type": "string",
//					"analyzer": "ik_max_word",
//					"search_analyzer": "ik_smart"
//				},
//				"path": {
//                "type": "string"
//				},
//				"percent": {
//                "type": "long"
//				},
//				"r": {
//                "type": "long"
//				},
//				"scid": {
//                "type": "long"
//				},
//				"sell": {
//                "type": "long"
//				},
//				"share": {
//                "type": "long"
//				},
//				"shoucang": {
//                "type": "long"
//				},
//				"shoucolor": {
//                "type": "long"
//				},
//				"size": {
//                "type": "long"
//				},
//				"suffix": {
//                "type": "string"
//				},
//				"sum": {
//                "type": "long"
//				},
//				"time": {
//                "type": "long"
//				},
//				"title": {
//                "type": "string",
//					"analyzer": "ik_max_word",
//					"search_analyzer": "ik_smart"
//				},
//				"tui": {
//                "type": "long"
//				},
//				"tuitime": {
//                "type": "long"
//				},
//				"yesterday": {
//                "type": "long"
//				},
//				"yid": {
//                "type": "long"
//				},
//				"label": {
//                "type": "string",
//					"analyzer": "ik_max_word",
//					"search_analyzer": "ik_smart"
//				},
//				"status": {
//                "type": "long"
//				}
//			}
//		}
//	}
//}