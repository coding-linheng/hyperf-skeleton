<?php

declare(strict_types=1);

namespace App\Task\Consumer\LoggerPlan\Handler;

use App\Services\RequestLogService;

class RecordRequestLog
{
    public function __invoke(array $data): bool
    {
        try {
            $loggerData = [
                'ip'             => $data['ip'],
                'uid'            => $data['user_code'],
                'uri'            => $data['uri'],
                'type'           => 1,
                'refer'          => $data['refer'][0] ?? '',
                'user_agent'     => $data['user_agent'][0] ?? '',
                'request_params' => json_encode($data['request_params']),
                'request_method' => $data['request_method'],
                'create_time'    => date('Y-m-d H:i:s', $data['create_time']),
            ];

            di()->get(RequestLogService::class)->insert($loggerData);
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
        return true;
    }
}
