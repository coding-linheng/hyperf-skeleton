<?php

declare(strict_types=1);

namespace App\Task\Producer;

class ActivityPlanProducer extends BaseProducer
{
    /**
     * 队列名称.
     */
    protected string $queueName = 'activity_plan';

    /**
     * 上传素材.
     */
    public function uploadMaterial(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'UPLOAD_MATERIAL',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }

    /**
     * 上传文库.
     */
    public function uploadLibrary(array $data, int $delay = 0): bool
    {
        $newData = [
            'type' => 'UPLOAD_LIBRARY',
            'data' => $data,
        ];

        return $this->push($newData, $delay);
    }
}
