<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AbstractController;
use App\Model\Member;
use App\Request\User;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;
use Qbhy\HyperfAuth\AuthManager;

class ApiController extends AbstractController
{
    #[Inject]
    protected Member $memberModel;

    #[Inject]
    protected AuthManager $auth;

    public function Login(User $request): ResponseInterface
    {
        $request->scene('login')->validateResolved();
        $username = $this->request->post('username');
        $password = $this->request->post('password');
        $user     = make(\App\Model\User::class)->login($username, $password);
        $token    = $this->auth->guard('jwt')->login($user);
        return $this->response->success(['token' => $token]);
    }
}
