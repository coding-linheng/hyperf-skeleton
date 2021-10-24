<?php

declare(strict_types=1);

namespace App\Task\Consumer\ActivityPlan\Handler;

/**
 * 上传素材活动.
 */
class UploadMaterial
{
    public function __invoke(array $data): bool
    {
        echo __METHOD__ . PHP_EOL;
        return true;
    }
}
