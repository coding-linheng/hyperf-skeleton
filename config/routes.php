<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

use App\Controller\Utils;
use App\Controller\V1\UserCenter\UserController;
use App\Middleware\JwtMiddleware;
use Hyperf\HttpServer\Router\Router;

#当前项目总路径
$routerPath = env('API_BASE_URL', '/v1');

#公用部分
Router::post($routerPath . '/login', [App\Controller\V1\ApiController::class, 'Login']);
Router::post($routerPath . '/logout', [App\Controller\V1\ApiController::class, 'Logout'], ['middleware' => [JwtMiddleware::class]]);
Router::addGroup($routerPath . '/', function () {
    #用户相关
    Router::addGroup('user/', function () {
        Router::post('getUserinfo', [UserController::class, 'getUserinfo']);
    });

    #工具类
    Router::addGroup('utils/', function () {
        Router::post('upload', [Utils::class, 'upload']);
    });

}, ['middleware' => [JwtMiddleware::class]]);

Router::get('/favicon.ico', static function () {
    return '';
});
