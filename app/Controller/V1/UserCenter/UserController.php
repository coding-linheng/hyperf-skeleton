<?php

declare(strict_types=1);

namespace App\Controller\V1\UserCenter;

use App\Common\Sms;
use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Exception\BusinessException;
use App\Repositories\V1\UserRepository;
use App\Request\User;
use App\Services\SmsService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class UserController extends AbstractController
{
    #[Inject]
    protected SmsService $smsService;

    /*
     * 获取用户信息.
     */
    public function getUserinfo(): ResponseInterface
    {
        return $this->response->success(user());
    }

    /*
     * 绑定手机号
     */
    public function bindMobile(User $request): ResponseInterface
    {
        $request->scene('bind_mobile')->validateResolved();
        $mobile  = $request->post('mobile');
        $captcha = $request->post('captcha');
        $event   = 'verify';

        if (empty($this->smsService->check($mobile, $captcha))) {
            throw new BusinessException(ErrorCode::ERROR, '验证码错误或已过期');
        }
        $user      = make(UserRepository::class)->getUserData(user()['id']);
        $user->tel = $mobile;
        $user->save();
        Sms::flush($mobile, $event);
        return $this->success();
    }

    /*
     * 用户资料
     */
    public function profile(User $request): ResponseInterface
    {
        $request->scene('profile')->validateResolved();
        $params = $request->all();
        $user   = make(UserRepository::class)->getUser(user()['id']);
        $user->fill($params)->save();
        return $this->success();
    }

    /*
     * 申请
     */
    public function certification(User $request): ResponseInterface
    {
        $request->scene('certification')->validateResolved();
        $params   = $request->all();
        $userData = make(UserRepository::class)->getUserData(user()['id']);

        $userData->name     = $params['name'];
        $userData->tel      = $params['mobile'];
        $userData->cardnum  = $params['id_card'];
        $userData->zhi      = $params['alipay'];
        $userData->qq       = $params['qq'];
        $userData->email    = $params['email'];
        $userData->cardimg  = $params['id_card_true'];
        $userData->cardimg1 = $params['id_card_false'];
        $userData->save();
        return $this->success();
    }
}
