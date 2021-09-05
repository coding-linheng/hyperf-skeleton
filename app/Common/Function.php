<?php

use Hyperf\Utils\ApplicationContext;
use Psr\Http\Message\ServerRequestInterface;

if (!function_exists('user')) {
    /**
     * jwt用户信息
     */

    function user()
    {
        $request = ApplicationContext::getContainer()->get(ServerRequestInterface::class);
        return $request->getAttribute('user', []);
    }
}
