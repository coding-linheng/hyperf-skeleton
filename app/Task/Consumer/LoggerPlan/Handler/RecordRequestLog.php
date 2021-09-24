<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Handler;

use App\Constants\TaskStatus;
use App\Task\Producer\AccountPlanProducer;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Codec\Json;


class RecordRequestLog
{


    public function __invoke(array $data)
    {
        try {
            var_dump($data);
        } catch (\Throwable $exception) {
            var_dump($data);
        } finally {
            var_dump($data);
        }
        return true;
    }

}