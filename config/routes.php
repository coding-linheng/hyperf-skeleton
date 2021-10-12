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
use App\Controller\V1\HelpCenter;
use App\Controller\V1\Index\AlbumCollectController;
use App\Controller\V1\Index\SucaiController;
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
        Router::get('getUserIncome', [UserController::class, 'getUserIncome']);
        Router::get('getMoneyLog', [UserController::class, 'getMoneyLog']);
        Router::get('getCashLog', [UserController::class, 'getCashLog']);
        Router::get('getScoreLog', [UserController::class, 'getScoreLog']);
        Router::get('getPrivateMessage', [UserController::class, 'getPrivateMessage']);
        Router::get('getSystemMessage', [UserController::class, 'getSystemMessage']);
        Router::get('getMessageDetail', [UserController::class, 'getMessageDetail']);
        Router::get('getMoving', [UserController::class, 'getMoving']);
        Router::get('worksManage', [UserController::class, 'worksManage']);
        Router::get('getMaterialCategory', [UserController::class, 'getMaterialCategory']);
        Router::get('getMaterialFormat', [UserController::class, 'getMaterialFormat']);
        Router::put('uploadHeadImg', [UserController::class, 'uploadHeadImg']);
        Router::put('bindMobile', [UserController::class, 'bindMobile']);
        Router::put('profile', [UserController::class, 'profile']);
        Router::put('certification', [UserController::class, 'certification']);
        Router::put('writeInformationForMaterial', [UserController::class, 'writeInformationForMaterial']);
        Router::post('cash', [UserController::class, 'cash']);
        Router::post('uploadWork', [UserController::class, 'uploadWork']);
    });

    #灵感
    Router::addGroup('album/', function () {
        Router::get('getDetail', [AlbumController::class, 'getDetail']);
        Router::get('getAlbumAuthor', [AlbumController::class, 'getAlbumAuthor']);
        Router::get('getOriginAlbumPic', [AlbumController::class, 'getOriginAlbumPic']);
        Router::post('captureAlbumImg', [AlbumCollectController::class, 'captureAlbumImg']);
        Router::post('collectAlbumImg', [AlbumCollectController::class, 'collectAlbumImg']);
    });

    #素材
    Router::addGroup('material/', function () {
        Router::post('collectImg', [SucaiController::class, 'collectImg']);
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
        Router::get('getRandList', [AlbumController::class, 'getRandList']);
        Router::get('searchList', [AlbumController::class, 'searchList']);
    });

    #素材
    Router::addGroup('material/', function () {
        Router::get('searchImgList', [SucaiController::class, 'searchImgList']);
    });

    #短信类
    Router::addGroup('sms/', function () {
        Router::post('send', [Sms::class, 'send']);
    });

    #帮助中心
    Router::addGroup('help/', function () {
        Router::get('getHelpList', [HelpCenter::class, 'getHelpList']);
        Router::get('getHelpDetail', [HelpCenter::class, 'getHelpDetail']);
        Router::get('getQuestionList', [HelpCenter::class, 'getQuestionList']);
        Router::get('getMoreQuestion', [HelpCenter::class, 'getMoreQuestion']);
        Router::post('FeedbackQuestion', [HelpCenter::class, 'FeedbackQuestion']);
    });
});
Router::get('/favicon.ico', static function () {
    return '';
});
