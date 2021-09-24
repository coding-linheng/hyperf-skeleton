<?php

declare(strict_types=1);

namespace App\Task\Consumer\AccountPlan\Handler;

class Login
{
    public function __invoke(array $data)
    {
        return true;
    }
}
