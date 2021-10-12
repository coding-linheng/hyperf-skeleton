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
    use Searchable;

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
    protected $fillable = ['id', 'unnum', 'uid', 'path', 'suffix', 'size', 'name', 'status', 'del', 'img', 'title', 'leixing', 'price', 'ttime', 'dtime', 'text', 'shoucang', 'downnum', 'tui', 't_time', 'time', 'week', 'weekguanzhu', 'looknum', 'height', 'yesterday', 'll', 'guanjianci', 'g_time', 'mulu_id', 'geshi_id'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = ['id' => 'integer', 'unnum' => 'integer', 'uid' => 'integer', 'size' => 'integer', 'status' => 'integer', 'del' => 'integer', 'leixing' => 'integer', 'ttime' => 'integer', 'dtime' => 'integer', 'shoucang' => 'integer', 'downnum' => 'integer', 'tui' => 'integer', 't_time' => 'integer', 'time' => 'integer', 'week' => 'integer', 'weekguanzhu' => 'integer', 'looknum' => 'integer', 'height' => 'integer', 'yesterday' => 'integer', 'll' => 'integer', 'g_time' => 'integer'];

    // put http://119.23.59.3:9200/dc45
//{
//"mappings": {
//"dc10000albumlist": {
//"properties": {
//"aid": {
//"type": "long"
//},
//"b": {
//  "type": "long"
//          },
//          "caiji": {
//  "type": "long"
//          },
//          "cid": {
//  "type": "long"
//          },
//          "color": {
//  "type": "string"
//          },
//          "color_id": {
//  "type": "long"
//          },
//          "colorfenlei": {
//  "type": "long"
//          },
//          "colorlastweek": {
//  "type": "long"
//          },
//          "colortime": {
//  "type": "long"
//          },
//          "coloryesterday": {
//  "type": "long"
//          },
//          "count": {
//  "type": "long"
//          },
//          "del": {
//  "type": "long"
//          },
//          "downnum": {
//  "type": "long"
//          },
//          "dtime": {
//  "type": "long"
//          },
//          "fenlei": {
//  "type": "long"
//          },
//          "g": {
//  "type": "long"
//          },
//          "g_time": {
//  "type": "long"
//          },
//          "height": {
//  "type": "long"
//          },
//          "id": {
//  "type": "long"
//          },
//          "is_color": {
//  "type": "long"
//          },
//          "jtui": {
//  "type": "long"
//          },
//          "jtuitime": {
//  "type": "long"
//          },
//          "label": {
//  "type": "string",
//            "analyzer": "ik_max_word",
//            "search_analyzer": "ik_smart"
//          },
//          "laiyuan": {
//  "type": "string"
//          },
//          "lastweek": {
//  "type": "long"
//          },
//          "looknum": {
//  "type": "long"
//          },
//          "name": {
//  "type": "string",
//            "analyzer": "ik_max_word",
//            "search_analyzer": "ik_smart"
//          },
//          "path": {
//  "type": "string"
//          },
//          "percent": {
//  "type": "long"
//          },
//          "r": {
//  "type": "long"
//          },
//          "scid": {
//  "type": "long"
//          },
//          "sell": {
//  "type": "long"
//          },
//          "share": {
//  "type": "long"
//          },
//          "shoucang": {
//  "type": "long"
//          },
//          "shoucolor": {
//  "type": "long"
//          },
//          "size": {
//  "type": "long"
//          },
//          "status": {
//  "type": "long"
//          },
//          "suffix": {
//  "type": "string"
//          },
//          "sum": {
//  "type": "long"
//          },
//          "time": {
//  "type": "long"
//          },
//          "title": {
//  "type": "string",
//            "analyzer": "ik_max_word",
//            "search_analyzer": "ik_smart"
//          },
//          "tui": {
//  "type": "long"
//          },
//          "tuitime": {
//  "type": "long"
//          },
//          "yesterday": {
//  "type": "long"
//          },
//          "yid": {
//  "type": "long"
//          }
//        }
//      },
//    "dc10000img": {
//  "properties": {
//    "del": {
//      "type": "long"
//      },
//      "downnum": {
//      "type": "long"
//      },
//      "dtime": {
//      "type": "long"
//      },
//      "g_time": {
//      "type": "long"
//      },
//      "guanjianci": {
//      "type": "string"
//      },
//      "height": {
//      "type": "long"
//      },
//      "id": {
//      "type": "long"
//      },
//      "img": {
//      "type": "string"
//      },
//      "leixing": {
//      "type": "long"
//      },
//      "ll": {
//      "type": "long"
//      },
//      "looknum": {
//      "type": "long"
//      },
//      "name": {
//      "type": "string",
//          "analyzer": "ik_max_word",
//          "search_analyzer": "ik_smart"
//      },
//      "path": {
//      "type": "string"
//      },
//      "price": {
//      "type": "long"
//      },
//      "shoucang": {
//      "type": "long"
//      },
//      "size": {
//      "type": "long"
//      },
//      "status": {
//      "type": "long"
//      },
//      "suffix": {
//      "type": "string"
//      },
//      "t_time": {
//      "type": "long"
//      },
//      "text": {
//      "type": "string"
//      },
//      "time": {
//      "type": "long"
//      },
//      "title": {
//      "type": "string",
//            "analyzer": "ik_max_word",
//            "search_analyzer": "ik_smart"
//      },
//      "ttime": {
//      "type": "long"
//      },
//      "tui": {
//      "type": "long"
//      },
//      "uid": {
//      "type": "long"
//      },
//      "unnum": {
//      "type": "long"
//      },
//      "week": {
//      "type": "long"
//      },
//      "weekguanzhu": {
//      "type": "long"
//      },
//      "yesterday": {
//      "type": "long"
//      }
//    }
//  },
//      "dc10000albumlistcolor": {
//  "properties": {
//    "b": {
//      "type": "long"
//          },
//          "color": {
//      "type": "string"
//          },
//          "count": {
//      "type": "long"
//          },
//          "g": {
//      "type": "long"
//          },
//          "id": {
//      "type": "long"
//          },
//          "num": {
//      "type": "long"
//          },
//          "percent": {
//      "type": "long"
//          },
//          "pid": {
//      "type": "long"
//          },
//          "r": {
//      "type": "long"
//          }
//        }
//      }
//    }
//}
}
