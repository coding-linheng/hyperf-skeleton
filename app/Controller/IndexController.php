<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use App\Middleware\JwtMiddleware;
use App\Model\Member;
use App\Model\Sms as SmsModel;
use App\Services\CommonService;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\ApplicationContext;
use HyperfLibraries\Sms\Contract\SmsInterface;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Qbhy\HyperfAuth\AuthManager;

#[AutoController]
class IndexController extends AbstractController
{
    #[Inject]
    protected Member $memberModel;

    #[Inject]
    protected AuthManager $auth;

    #[Inject]
    protected CommonService $commonService;

    public function index(): array
    {
        $user   = $this->request->input('user', 'Hyperf111123123123');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    /**
     * 首页推荐用户列表.
     * @param: type 1 素材类 2 灵感类
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRecommendUserList()
    {
        $type=$this->request->input('type',1);
        $list = $this->commonService->getRecommendUserList($type);
        return $this->response->success($list);
    }

    /**
     * 首页推荐作品列表.
     * @param: type 1 素材类 2 灵感类,默认素材类
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getRecommendZpList()
    {
        $type=$this->request->input('type',1);
        $list = $this->commonService->getRecommendZpList($type);
        return $this->response->success($list);
    }

    /**
     * 获取首页轮播图.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndexBanner()
    {
        $list = $this->commonService->getBannerIndex();
        return $this->response->success($list);
    }

    /**
     * 获取首页广告位.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getAdvertisement()
    {
        $list = $this->commonService->getAdvertisement();
        return $this->response->success($list);
    }

    /**
     * 获取首页顶部广告位.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getIndexTopAdvertisement()
    {
        $list = $this->commonService->getIndexTopAdvertisement();
        return $this->response->success($list);
    }

    /**
     * 获取友情链接.
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function getBlogRoll()
    {
        $list = $this->commonService->getBlogRoll();
        return $this->response->success($list);
    }

    public function add(\App\Request\Member $request)
    {
        //$request->scene('edit')->validateResolved();
        $mobile = $request->post('mobile', 111);
        $name   = $request->post('name', 111);
        $member = new Member();

        $member->nickname = $name;
        $member->username = $name;
        $member->mobile   = (string)$mobile;
        $member->password = md5('123456');
        $member->save();
        return $this->response->success();
    }

    #[Middleware(JwtMiddleware::class)]
    public function user()
    {
        $user = user();
        var_dump($user);
    }

    public function sms()
    {
        try {
            $easySms = ApplicationContext::getContainer()->get(SmsInterface::class);
            $code    = mt_rand(100000, 999999);
            $easySms->send('18458089188', [
                'template' => 'SMS_119911016',
                'data'     => [
                    'code' => $code,
                ],
            ]);
            //验证码入库
            SmsModel::create([
                'code'        => $code,
                'mobile'      => '18458089188',
                'ip'          => get_client_ip(),
                'event'       => 'verify',
                'create_time' => time(),
            ]);
            return true;
        } catch (InvalidArgumentException|NoGatewayAvailableException $e) {
            return $this->success($e->getResults());
        }
    }
}
