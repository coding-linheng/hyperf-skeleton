<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\RequestLog as RequestLogModel;
use App\Repositories\BaseRepository;

/**
 * RequestLogRepository.
 */
class RequestLogRepository extends BaseRepository
{
    public function Insert($insertData): int
    {
        return RequestLogModel::insertGetId($insertData);
    }
}
