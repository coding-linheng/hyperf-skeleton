<?php

declare(strict_types=1);

namespace App\Task\Consumer;

use Hyperf\AsyncQueue\Job;

class ConsumerJob extends Job
{
    /**
     * @var array
     */
    public $params;

    public function __construct(array $params)
    {
        $this->params = $params;
    }

    public function handle()
    {
        return $this->params;
    }
}
