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

use App\Controller\Sms;
use App\Controller\Utils;
use App\Controller\V1\UserCenter\UserController;
use App\Controller\V1\Index\AlbumController;
use App\Middleware\JwtMiddleware;
use Hyperf\HttpServer\Router\Router;

#当前项目总路径
$routerPath = env('API_BASE_URL', '/v1');

#公用部分
Router::Get($routerPath . '/getRcpStatics', [App\Controller\IndexController::class, 'getRcpStatics']);
Router::post($routerPath . '/login', [App\Controller\V1\ApiController::class, 'Login']);
Router::post($routerPath . '/logout', [App\Controller\V1\ApiController::class, 'Logout'],
    ['middleware' => [JwtMiddleware::class]]);
Router::addGroup($routerPath . '/', function () {
    #用户相关
    Router::addGroup('user/', function () {
        Router::get('getUserinfo', [UserController::class, 'getUserinfo']);
        Router::put('bindMobile', [UserController::class, 'bindMobile']);
        Router::put('profile', [UserController::class, 'profile']);
        Router::put('certification', [UserController::class, 'certification']);
        Router::get('getUserIncome', [UserController::class, 'getUserIncome']);
    });

    #工具类
    Router::addGroup('utils/', function () {
        Router::post('upload', [Utils::class, 'upload']);
    });

}, ['middleware' => [JwtMiddleware::class]]);

//无需登录也可以访问的前端页面接口
Router::addGroup($routerPath . '/', function () {
    #灵感
    Router::addGroup('album/', function () {
        Router::get('getList', [AlbumController::class, 'getList']);
    });
    #短信类
    Router::addGroup('sms/', function () {
        Router::post('send', [Sms::class, 'send']);
    });
});
Router::get('/favicon.ico', static function () {
    return '';
});
