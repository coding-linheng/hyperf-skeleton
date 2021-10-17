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

use App\Controller\IndexController;
use App\Controller\Sms;
use App\Controller\Utils;
use App\Controller\V1\HelpCenter;
use App\Controller\V1\Index\AlbumCollectController;
use App\Controller\V1\Index\SucaiController;
use App\Controller\V1\Index\WenkuController;
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
        Router::get('getDetailForMaterial', [UserController::class, 'getDetailForMaterial']);
        Router::put('uploadHeadImg', [UserController::class, 'uploadHeadImg']);
        Router::put('bindMobile', [UserController::class, 'bindMobile']);
        Router::put('profile', [UserController::class, 'profile']);
        Router::put('certification', [UserController::class, 'certification']);
        Router::put('writeInformationForMaterial', [UserController::class, 'writeInformationForMaterial']);
        Router::post('cash', [UserController::class, 'cash']);
        Router::post('uploadWork', [UserController::class, 'uploadWork']);
        Router::delete('deleteForMaterial', [UserController::class, 'deleteForMaterial']);
        Router::delete('batchDeleteMaterial', [UserController::class, 'batchDeleteMaterial']);
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
        Router::get('getDetail', [SucaiController::class, 'getDetail']);
        Router::get('getDownUrl', [SucaiController::class, 'getDownUrl']);
        Router::get('getListByAuthor', [SucaiController::class, 'getListByAuthor']);
    });

    #文库
    Router::addGroup('document/', function () {
        Router::get('getDetail', [WenkuController::class, 'getDetail']);
        Router::get('getDownUrl', [WenkuController::class, 'getDownUrl']);
        Router::get('getListByAuthor', [WenkuController::class, 'getListByAuthor']);
    });

    #工具类
    Router::addGroup('utils/', function () {
        Router::post('upload', [Utils::class, 'upload']);
    });

}, ['middleware' => [JwtMiddleware::class]]);

//无需登录也可以访问的前端页面接口
Router::addGroup($routerPath . '/', function () {


    #首页
    Router::addGroup('index/', function () {
      Router::get('getIndexBanner', [IndexController::class, 'getIndexBanner']);
      Router::get('getAdvertisement', [IndexController::class, 'getAdvertisement']);
      Router::get('getIndexTopAdvertisement', [IndexController::class, 'getIndexTopAdvertisement']);
      Router::get('getBlogRoll', [IndexController::class, 'getBlogRoll']);
      Router::get('getRecommendUserList', [IndexController::class, 'getRecommendUserList']);
      Router::get('getRecommendZpList', [IndexController::class, 'getRecommendZpList']);
    });

    #灵感
    Router::addGroup('album/', function () {
        Router::get('getRandList', [AlbumController::class, 'getRandList']);
        Router::get('searchList', [AlbumController::class, 'searchList']);
        Router::post('getOriginalWorkList', [AlbumController::class, 'getOriginalWorkList']);
        Router::post('getBrandCollectionList', [AlbumController::class, 'getBrandCollectionList']);
        Router::post('getLandedCollectionList', [AlbumController::class, 'getLandedCollectionList']);
    });

    #素材
    Router::addGroup('material/', function () {
        Router::get('searchList', [SucaiController::class, 'searchImgList']);
        Router::get('recommendList', [SucaiController::class, 'recommendList']);
    });

    #文库
    Router::addGroup('document/', function () {
        Router::get('searchList', [WenkuController::class, 'getList']);
        Router::get('recommendList', [WenkuController::class, 'recommendList']);
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
