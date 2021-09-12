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
use Hyperf\HttpServer\Router\Router;
#use App\Middleware\JwtMiddleware;

#当前项目总路径
$routerPath = env('API_BASE_URL', '/v1');

#公用部分
Router::post($routerPath . '/login', [App\Controller\V1\ApiController::class, 'login']);
Router::post($routerPath . '/logout', [App\Controller\V1\ApiController::class, 'logout']);
Router::addGroup($routerPath . '/', function () {
 // Router::post('api/index', [App\Controller\V1\ApiController::class, 'index']);
  #用户
//  Router::addGroup('user/', function () {
//    Router::post('list', [App\Controller\Admin\UserController::class, 'index']);
//    Router::post('user_list', [App\Controller\Admin\UserController::class, 'userList']);
//    Router::post('store', [App\Controller\Admin\UserController::class, 'store']);
//    Router::post('get_info', [App\Controller\Admin\UserController::class, 'getInfo']);
//    Router::post('get_roles', [App\Controller\Admin\UserController::class, 'getRoles']);
//    Router::post('delete', [App\Controller\Admin\UserController::class, 'destroy']);
//    Router::post('getGoogleCode', [App\Controller\Admin\GetGoogleCode::class, 'handle']);
//    Router::post('checkGoogleCode', [App\Controller\Admin\CheckGoogleCode::class, 'handle']);
//  });

  #上传
//  Router::addGroup('upload/', function () {
//    Router::post('get_upload_token', [App\Controller\Admin\UploadController::class, 'getUploadToken']);
//    Router::post('file', [App\Controller\Admin\UploadController::class, 'uploadFile']);
//  });

  #轮播图管理
//  Router::addGroup('carousel/', function () {
//    Router::post('list', [App\Controller\Admin\CarouselController::class, 'index']);
//    Router::post('store', [App\Controller\Admin\CarouselController::class, 'store']);
//    Router::post('get_info', [App\Controller\Admin\CarouselController::class, 'getInfo']);
//    Router::post('delete', [App\Controller\Admin\CarouselController::class, 'destroy']);
//    Router::post('order', [App\Controller\Admin\CarouselController::class, 'orderCarousel']);
//    Router::post('type_list', [App\Controller\Admin\CarouselController::class, 'typeList']);
//  });

}, ['middleware' => [App\Middleware\JwtMiddleware::class]]);

Router::get('/favicon.ico', static function () {
    return '';
});
