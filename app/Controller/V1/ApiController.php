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

namespace App\Controller\V1;

use App\Middleware\JwtMiddleware;
use App\Model\Albumlist;
use App\Model\Member;
use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Qbhy\HyperfAuth\AuthManager;
use App\Controller\AbstractController;

#[AutoController]
class ApiController extends AbstractController
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

    public function loginOut()
    {
        $albumlist = new Albumlist();
        $start     = time();
        //自定义闭包搜索  可以改变搜索方式  demo如下
        $callback  = function ($client, $builder, $params) {
            $params['body']['query']['bool']['must'] = [[
                'query_string' => [
                    'query'         => '海报',
                    'default_field' => 'title',
                ],
            ]];
            return $client->search($params);
        };
        $count     = $albumlist::search('', $callback)->raw();
        return $this->response->success(['list' => $count, 'start' => $start, 'end' => time()]);
    }

    public function login()
    {
        /** @var User $user */
        $user = User::query()->where('id', 29928)->first();
        $user->setId($user['id']);
        return $this->auth->guard('jwt')->login($user);
    }

    #[Middleware(JwtMiddleware::class)]
    public function user()
    {
        $user = user();
        var_dump($user);
    }
}
