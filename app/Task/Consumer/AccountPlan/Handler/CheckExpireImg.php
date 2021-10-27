<?php

declare(strict_types=1);

namespace App\Task\Consumer\AccountPlan\Handler;

use App\Model\Img;
use Hyperf\DbConnection\Db;

class CheckExpireImg
{
    public function __invoke(array $data): bool
    {
        Db::beginTransaction();
        try {
            $expireTime = strtotime('-60 days');
            $where      = [['time', '<=', $expireTime], ['status', '=', 0]]; //逻辑删除60天内未处理的素材
            Img::query()->where($where)->whereIn('uid', $data)->update(['del' => 0]);
            Db::commit();
        } catch (\Exception $e) {
            Db::rollBack();
            $message = sprintf('%s[%s] in %s', $e->getMessage(), $e->getLine(), $e->getFile());
            logger('check_expire_img', 'queue')->debug($message);
        }
        return true;
    }
}
