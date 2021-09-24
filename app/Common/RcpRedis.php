<?php

declare(strict_types=1);

namespace App\Common;

use Hyperf\Redis\Redis;

class RcpRedis extends Redis
{
    // 对应的 Pool 的 key 值
    protected $poolName = 'rcp';
}