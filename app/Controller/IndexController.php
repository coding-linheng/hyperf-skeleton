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

use App\Common\Rcp;
use App\Middleware\JwtMiddleware;
use App\Model\Albumlist;
use App\Model\Member;
use App\Model\Sms as SmsModel;
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

    public function index(): array
    {
        $user   = $this->request->input('user', 'Hyperf111123123123');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function add(\App\Request\Member $request)
    {
//        $request->scene('edit')->validateResolved();
        $mobile = $request->post('mobile', 111);
        $name   = $request->post('name', 111);
        $member = new Member();

        $member->nickname = $name;
        $member->username = $name;
        $member->mobile   = (string) $mobile;
        $member->password = md5('123456');
        $member->save();
        return $this->response->success();
    }

    public function elasticsearch()
    {
        $albumlist = new Albumlist();
        $start     = time();
        $count     = $albumlist->where('title', 'like', '%海报%')->count();
        $list      = $albumlist->where('title', 'like', '%海报%')->limit(100)->get();
        return $this->response->success(['count' => $count, 'list' => $list, 'start' => $start, 'end' => time()]);
    }

    public function demo()
    {
        $albumlist = new Albumlist();
        $start     = time();
        //自定义闭包搜索  可以改变搜索方式  demo如下
        $callback = function ($client, $builder, $params) {
            $params['body']['query']['bool']['must'] = [
                [
                    'query_string' => [
                        'query'         => '海报',
                        'default_field' => 'title',
                    ],
                ],
            ];
            return $client->search($params);
        };
        $count    = $albumlist::search('', $callback)->raw();
        return $this->response->success(['list' => $count, 'start' => $start, 'end' => time()]);
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
            var_dump($e->getResults());
            return $this->success($e->getResults());
        }
    }

    public function test()
    {
        echo di()->get(Rcp::class)->get() . PHP_EOL;
        echo di()->get(Rcp::class)->get() . PHP_EOL;
        echo di()->get(Rcp::class)->get() . PHP_EOL;
    }

    public function getRcpStatics(){
      $res=  di()->get(Rcp::class)->getRcpStatics();
      return $this->success($res);
    }
}
