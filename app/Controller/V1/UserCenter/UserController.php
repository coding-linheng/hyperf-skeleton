<?php

declare(strict_types=1);

namespace App\Controller\V1\UserCenter;

use App\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

class UserController extends AbstractController
{
    /**
     * 获取用户信息.
     */
    public function getUserinfo(): ResponseInterface
    {
        return $this->response->success(user());
    }
}
