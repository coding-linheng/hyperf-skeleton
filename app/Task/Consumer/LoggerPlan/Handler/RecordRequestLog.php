<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Handler;

class RecordRequestLog
{
    public function __invoke(array $data): bool
    {
        try {
            echo __CLASS__ . PHP_EOL;
        } catch (\Throwable $exception) {
            var_dump($data);
        } finally {
            var_dump($data);
        }
        return true;
    }
}
