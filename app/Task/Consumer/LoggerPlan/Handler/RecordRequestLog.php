<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Handler;

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
