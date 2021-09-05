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
            'message' => "Hello $user.",
        ];
    }

    public function add(\App\Request\Member $request): array
    {
        $request->scene('test')->validateResolved();
        return $request->all();
    }

    public function login()
    {
        /** @var User $user */
        $user = User::query()->where('id', 29928)->first();
        $user->setId($user['id']);
        return $this->auth->login($user);
    }

    #[Middleware(JwtMiddleware::class)]
    public function user()
    {
        $user = user();
        var_dump($user);
    }
}