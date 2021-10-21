<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Daywaterdc;
use App\Model\Monthwaterdc;
use App\Model\User;
use App\Model\Userdata;
use App\Model\Waterdc;
use App\Model\Waterdo;
use App\Model\Waterdown;
use App\Model\Waterscore;
use App\Model\Weekwaterdc;
use App\Repositories\BaseRepository;

/**
 * 流水.
 */
class WaterDoRepository extends BaseRepository
{
    /**
     * 增加操作日志.
     * $type 1关注专辑；2保存专辑；3关注我；4采集图片；5收藏文库；6收藏灵感；7收藏素材；8取消收藏素材；9取消收藏文库；10取消收藏灵感；11取消关注人.
     * @param mixed $doid
     * @param mixed $uid
     * @param mixed $cid
     * @param mixed $type
     * @param mixed $zid
     * @param mixed $original
     * @param mixed $aid
     */
    public function addWaterDo($doid, $uid, $cid, $type, $zid = 0, $original = 1, $aid = 0): int
    {
        $add             = [];
        $add['doid']     = $doid;
        $add['uid']      = $uid;
        $add['cid']      = $cid;
        $add['type']     = $type;
        $add['zid']      = $zid;
        $add['original'] = $original;
        $add['aid']      = $aid;
        $add['time']     = time();
        return Waterdo::insertGetId($add);
    }

    //添加下载素材流水
    public function addWaterDown($wid, $bid, $uid, $score = 0, $dc = 0)
    {
        $add          = [];
        $add['wid']   = $wid;
        $add['bid']   = $bid;
        $add['uid']   = $uid;
        $add['score'] = $score;
        $add['dc']    = $dc;
        $add['type']  = 2;
        $add['time']  = time();
        return Waterdown::insertGetId($add);
    }

    /**
     * 添加下载素材流水.
     * @param mixed $data
     */
    public function addWaterDownData($data): int
    {
        return Waterdown::insertGetId($data);
    }

    /**
     * 添加下载原创素材流水.
     * @param mixed $data
     */
    public function addWaterDc($data): int
    {
        return Waterdc::insertGetId($data);
    }

    /**
     * 给主人增加积分.
     * @param mixed $uid
     * @param mixed $score
     * @param mixed $wid
     * @param mixed $status
     * @param mixed $type
     * @param mixed $bid
     * @param mixed $title
     */
    public function addUserScore($uid, $score, $wid, $status, $type, $bid, $title): bool
    {
        if (!User::query()->where(['id' => $uid])->increment('score', $score)) {
            return false;
        }
        $add           = [];
        $add['uid']    = $uid;
        $add['score']  = $score;
        $add['type']   = $type;
        $add['status'] = $status;
        $add['wid']    = $wid;
        $add['bid']    = $bid;
        $add['name']   = $title;
        $add['time']   = time();

        if (!Waterscore::query()->insertGetId($add)) {
            return false;
        }
        return true;
    }

    /**
     * 给主人增加积分.
     * @param mixed $uid
     * @param mixed $score
     * @param mixed $wid
     * @param mixed $status
     * @param mixed $type
     * @param mixed $bid
     * @param mixed $title
     */
    public function addWaterScore($uid, $score, $wid, $status, $type, $bid, $title): bool
    {
        $add           = [];
        $add['uid']    = $uid;
        $add['score']  = $score;
        $add['type']   = $type;
        $add['status'] = $status; //素材下载扣除
        $add['wid']    = $wid;
        $add['bid']    = $bid;
        $add['name']   = $title;
        $add['time']   = time();

        if (!Waterscore::query()->insertGetId($add)) {
            return false;
        }
        return true;
    }

    /**
     * 给主人增加原创币
     *
     * @param $uid
     * @param $score
     * @param $wid
     * @param $status
     * @param int $type
     * @param $bid
     * @param $title
     * @param int $is_vip
     */
    public function addUserDc($uid, $score, $wid, $status, $type, $bid, $title, $is_vip = 0): bool
    {
        if (!User::query()->where(['id' => $uid])->increment('money', $score)) {
            return false;
        }
        $add           = [];
        $add['uid']    = $uid;
        $add['score']  = $score;
        $add['type']   = $type;
        $add['status'] = $status;
        $add['wid']    = $wid;
        $add['bid']    = $bid;
        $add['name']   = $title;
        $add['is_vip'] = $is_vip;
        $add['time']   = time();

        if (!Waterdc::query()->insertGetId($add)) {
            return false;
        }
        //统计总数
        if (!Userdata::query()->where(['uid' => $uid])->increment('total', $score)) {
            return false;
        }

        //日收入
        $time = strtotime(date('Y-m-d'));
        $info = Daywaterdc::query()->where(['uid' => $uid, 'time' => $time])->first();

        if (empty($info)) {
            $add         = [];
            $add['uid']  = $uid;
            $add['time'] = $time;
            $add['dc']   = $score;
            $ids         = Daywaterdc::query()->insertGetId($add);
        } else {
            $ids = Daywaterdc::query()->where(['id' => $info['id']])->increment('dc', $score);
        }

        if (!$ids) {
            return false;
        }
        //增加周统计
        if (date('w') == 0) {
            $day = 7;
        } else {
            $day = date('w');
        }
        $weekday  = $time - ($day) * 86400; //周一
        $weekInfo = Weekwaterdc::query()->where(['uid' => $uid, 'time' => $weekday])->first();

        if (empty($weekInfo)) {
            $add         = [];
            $add['uid']  = $uid;
            $add['time'] = $weekday;
            $add['dc']   = $score;
            $ids         = Weekwaterdc::query()->insertGetId($add);
        } else {
            $ids = Weekwaterdc::query()->where(['id' => $weekInfo['id']])->increment('dc', $score);
        }

        if (!$ids) {
            return false;
        }
        //增加月统计
        //先算出来本月一号
        $beginThismonth = mktime(0, 0, 0, (int)date('m'), 1, (int)date('Y'));
        $monthInfo      = Monthwaterdc::query()->where(['uid' => $uid, 'time' => $beginThismonth])->first();

        if (empty($monthInfo)) {
            $add         = [];
            $add['uid']  = $uid;
            $add['time'] = $beginThismonth;
            $add['dc']   = $score;
            $ids         = Monthwaterdc::query()->insertGetId($add);
        } else {
            $ids = Monthwaterdc::query()->where(['id' => $monthInfo['id']])->increment('dc', $score);
        }

        if (!$ids) {
            return false;
        }
        //增加月统计
        return true;
    }

    /**
     * 获取动态
     * @param array|string[] $column
     */
    public function getMoving(int $userid, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['uid', '=', $userid]];

        $orm   = Waterdo::from('waterdo as w')->join('user as u', 'u.id', 'w.doid')->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')
            ->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
        return ['count' => $count, 'list' => $list];
    }

    public function getMovingLimit(array $where, int $limit, array $column = ['*'])
    {
        return Waterdo::from('waterdo as w')->join('user as u', 'u.id', 'w.doid')->where($where)->select($column)->orderBy('id', 'desc')->limit($limit)->get();
    }
}
