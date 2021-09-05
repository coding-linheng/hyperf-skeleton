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

namespace App\Controller;

use App\Middleware\JwtMiddleware;
use App\Model\Member;
use App\Model\User;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\HttpServer\Annotation\Middleware;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Context;
use Qbhy\HyperfAuth\AuthManager;

#[AutoController]
class IndexController extends AbstractController
{
    #[Inject]
    protected Member $memberModel;

    #[Inject]
    protected AuthManager $auth;

    public function index()
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
        return $request->all();
    }

    public function login()
    {
        /** @var User $user */
        $user = User::query()->where('id', 29928)->first();
        $user->setId($user['id']);
        $token = $this->auth->login($user);
        $jwt   = $this->auth->guard('jwt')->getJwtManager()->parse($token);
        $uid   = $jwt->getPayload();
        var_dump($uid);
        return $token;
    }

    #[Middleware(JwtMiddleware::class)]
    public function user()
    {
        $user = user();
        var_dump($user);
    }
}