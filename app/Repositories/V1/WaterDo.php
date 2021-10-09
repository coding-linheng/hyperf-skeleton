<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Waterdo;
use App\Repositories\BaseRepository;

/**
 * 流水.
 */
class WaterDoRepository extends BaseRepository
{
    /**
     * 增加流水日志.
     * $type 1关注专辑；2保存专辑；3关注我.
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
}
