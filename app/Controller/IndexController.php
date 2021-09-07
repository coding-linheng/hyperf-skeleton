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
use App\Model\Albumlist;
use App\Model\Member;
use App\Model\User;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
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
        $user   = $this->request->input('user', 'Hyperf111');
        $method = $this->request->getMethod();

        return [
            'method'  => $method,
            'message' => "Hello {$user}.",
        ];
    }

    public function add(\App\Request\Member $request)
    {
        $request->scene('test')->validateResolved();
        $mobile = $request->post('mobile');
        $name   = $request->post('name');
        $member = new Member;

        $member->nickname = $name;
        $member->username = $name;
        $member->mobile   = (string)$mobile;
        $member->password = md5('123456');
        $member->save();
        return $this->response->success();
    }

    public function elasticsearch()
    {
        $albumlist = new Albumlist;
        $start     = time();
        $count     = $albumlist->where('title', 'like', '%海报%')->count();
        $list      = $albumlist->where('title', 'like', '%海报%')->limit(100)->get();
        return $this->response->success(['count' => $count, 'list' => $list, 'start' => $start, 'end' => time()]);
    }

    public function demo()
    {
        $albumlist = new Albumlist;
        $start     = time();
        $count     = $albumlist::search()->take(100)->get()->count();
        $list      = $albumlist::search()->take(100)->get();
        return $this->response->success(['count' => $count, 'list' => $list, 'start' => $start, 'end' => time()]);
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
