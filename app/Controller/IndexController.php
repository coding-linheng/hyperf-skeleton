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
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\ApplicationContext;
use HyperfLibraries\Sms\Contract\SmsInterface;
use Overtrue\EasySms\Exceptions\InvalidArgumentException;
use Overtrue\EasySms\Exceptions\NoGatewayAvailableException;
use Psr\Http\Message\ResponseInterface;
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
     *
     * @param: type 1 素材类 2 灵感类
     * {"code":0,"msg":"success","data":[
     * {"username":"test","nickname":"啊实打实","imghead":"https:\/\/image.codelin.ink\/public\/uploads\/5195ca1a3342bdce549382dcf2b89879.jpg","shoucang":25,"zhuanji":132,"zuopin":153,"sucainum":0,"wenkunum":0},
     * {"username":"Johnny","nickname":"Johnny","imghead":"http:\/\/thirdwx.qlogo.cn\/mmopen\/VvN4UQJFx7zmbQwaic4JPHHliaCnmicjibstIrw1L1j0QVErsUuzL466VNR1N3G9Ow36Uia8d7n4SayHAPcxhIWBVg614znjmvB6p\/132","shoucang":0,"zhuanji":0,"zuopin":0,"sucainum":0,"wenkunum":0},
     * ]}
     *
     * @return ResponseInterface
     */
    public function getRecommendUserList()
    {
        $type = $this->request->input('type', 1);
        $list = $this->commonService->getRecommendUserList($type);
        return $this->response->success($list);
    }

    /**
     * 首页推荐作品列表.
     * @param: type 1 素材类 2 灵感类,默认素材类
     * @return ResponseInterface
     */
    public function getRecommendZpList()
    {
        $type = $this->request->input('type', 1);
        $list = $this->commonService->getRecommendZpList($type);
        return $this->response->success($list);
    }

    /**
     * 获取首页轮播图.
     * @return ResponseInterface
     */
    public function getIndexBanner()
    {
        $list = $this->commonService->getBannerIndex();
        return $this->response->success($list);
    }

    /**
     * 获取首页广告位.
     * @return ResponseInterface
     */
    public function getAdvertisement()
    {
        $list = $this->commonService->getAdvertisement();
        return $this->response->success($list);
    }

    /**
     * 获取首页顶部广告位.
     * @return ResponseInterface
     */
    public function getIndexTopAdvertisement()
    {
        $list = $this->commonService->getIndexTopAdvertisement();
        return $this->response->success($list);
    }

    /**
     * 获取友情链接.
     * @return ResponseInterface
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

    //pdf转png图片
    public function test()
    {
        $res = '';
        try {
            $res = \App\Common\Utils::pdfToOnePng(BASE_PATH . '/public/pdf/aa.pdf', BASE_PATH . '/public/pdf/');
        } catch (\Exception $e) {
            echo $e->getMessage();
        }
        var_dump($res);
    }

    public function insertAll(): ResponseInterface
    {
        $data = [
            ['id' => 19, 'user_id' => 12123, 'gift' => 1, 'type' => 1, 'create_time' => 1],
            ['id' => 20, 'user_id' => 13333, 'gift' => 1, 'type' => 2, 'create_time' => 1],
            ['id' => 21, 'user_id' => 242, 'gift' => 1, 'type' => 1, 'create_time' => 1],
            ['id' => 22, 'user_id' => 245, 'gift' => 1, 'type' => 1, 'create_time' => 1],
            ['id' => 23, 'user_id' => 3655, 'gift' => 1, 'type' => 1, 'create_time' => 1],
            ['id' => 24, 'user_id' => 372, 'gift' => 1, 'type' => 1, 'create_time' => 1],
        ];
        $c    = 10;
        $s    = microtime(true);
        update_all($data, 'dczg_activity_log');
        $s = microtime(true) - $s;
        return $this->success(sprintf(
            "qps=%f, memory=%d, peak_memory=%d\n",
            $c / $s,
            memory_get_usage(true),
            memory_get_peak_usage(true)
        ));
    }

    public function pay(\App\Common\Pay $pay): ResponseInterface
    {
        $order = [
            'out_trade_no' => time() . '',
            'description'  => 'subject-测试',
            'notify_url'   => env('PAY_WECHAT_NOTIFY', 'https://meeting.codelin.ink/addons/epay/index/notifyx/type/wechat'),
            'amount'       => [
                'total'    => 1,
                'currency' => 'CNY',
            ],
            'payer'        => [
                'openid' => 'ozCs-408Pi6GHCnxIc59SyjqQwBA',
            ],
        ];

        return $this->success($pay->pay($order, 'wechat', 'mini'));
    }

    public function find(ConfigInterface $config): ResponseInterface
    {
        var_dump($config->get('hyperf_dc10000_db_host'));
    }
}
