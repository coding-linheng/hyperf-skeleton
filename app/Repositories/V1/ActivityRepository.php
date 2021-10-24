<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Signin;
use App\Model\SigninLog;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Model;

/**
 * 活动/签到库.
 */
class ActivityRepository extends BaseRepository
{
    /**
     * 查看是否签到.
     */
    public function getSignLog(array $where): bool
    {
        return SigninLog::query()->where($where)->exists();
    }

    /**
     * 获取用户签到信息.
     * @return Model|Signin
     */
    public function getSignInfo(array $where, array $column = ['*']): Model|Signin|null
    {
        return Signin::query()->where($where)->select($column)->first($where);
    }

    /**
     * 修改签到信息.
     */
    public function updateSignInfo(array $where, array $data): Model|Signin
    {
        return Signin::updateOrCreate($where, $data);
    }

    /**
     * 增加签到日志.
     */
    public function insertSignLog(array $data): int
    {
        return SigninLog::query()->insertGetId($data);
    }
}
