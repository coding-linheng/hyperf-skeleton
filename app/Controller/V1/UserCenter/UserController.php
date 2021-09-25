<?php

declare(strict_types=1);

namespace App\Controller\V1\UserCenter;

use App\Common\Sms;
use App\Constants\ErrorCode;
use App\Constants\UserCenterStatus;
use App\Controller\AbstractController;
use App\Exception\BusinessException;
use App\Request\User;
use App\Services\SmsService;
use App\Services\UserService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/**
 * 用户中心/资料处理.
 */
class UserController extends AbstractController
{
    #[Inject]
    protected SmsService $smsService;

    #[Inject]
    protected UserService $userService;

    /*
     * 获取用户信息.
     */
    public function getUserinfo(): ResponseInterface
    {
        $field = [
            'u.id', 'u.openid', 'u.unionid', 'u.nickname', 'u.imghead', 'u.email', 'u.sex', 'u.address', 'u.content',
            'u.score', 'u.dc', 'u.money', 'u.qi', 'u.fans', 'u.guan', 'u.isview', 'd.qq', 'u.wx',
        ];
        return $this->response->success($this->userService->getUserMerge(user()['id'], $field));
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
        $user      = $this->userService->getUserData(user()['id']);
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
        $user   = $this->userService->getUser(user()['id']);
        $user->fill($params)->save();
        return $this->success();
    }

    /*
     * 申请认证
     */
    public function certification(User $request): ResponseInterface
    {
        $request->scene('certification')->validateResolved();
        $params   = $request->all();
        $userData = $this->userService->getUserData(user()['id']);

        if ($userData->status == UserCenterStatus::USER_CERT_IS_PASS) {
            $this->error('已通过审核,不能修改');
        }

        $userData->name     = $params['name'];
        $userData->tel      = $params['mobile'];
        $userData->cardnum  = $params['id_card'];
        $userData->zhi      = $params['alipay'];
        $userData->qq       = $params['qq'];
        $userData->email    = $params['email'];
        $userData->cardimg  = $params['id_card_true'];
        $userData->cardimg1 = $params['id_card_false'];
        $userData->status   = UserCenterStatus::USER_CERT_IS_SUBMIT;
        $userData->save();
        return $this->success();
    }

    /*
     * 获取用户收入统计
     */
    public function getUserIncome(): ResponseInterface
    {
        $userid     = user()['id'];
        $userMerge  = $this->userService->getUserMerge($userid, ['u.dc', 'u.score', 'u.money', 'd.total']);
        $userIncome = $this->userService->getUserIncome($userid);
        return $this->success(array_merge($userMerge->toArray(), $userIncome));
    }
}
