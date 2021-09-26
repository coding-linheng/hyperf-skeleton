<?php

declare(strict_types=1);

namespace App\Controller\V1\UserCenter;

use App\Common\Sms;
use App\Constants\ErrorCode;
use App\Constants\UserCenterStatus;
use App\Controller\AbstractController;
use App\Exception\BusinessException;
use App\Repositories\V1\UserRepository;
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
            'u.id', 'u.nickname', 'u.imghead', 'u.email', 'u.sex', 'u.address', 'u.content', 'u.score', 'u.dc',
            'u.money', 'u.qi', 'u.fans', 'u.guan', 'u.isview', 'd.qq', 'u.wx', 'u.mobile', 'd.tel',
        ];
        return $this->success(make(UserRepository::class)->getUserMerge(user()['id'], $field));
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
        $user         = make(UserRepository::class)->getUser(user()['id']);
        $user->mobile = $mobile;
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
     * 申请认证
     */
    public function certification(User $request): ResponseInterface
    {
        $request->scene('certification')->validateResolved();
        $params   = $request->all();
        $userData = make(UserRepository::class)->getUserData(user()['id']);

        if ($userData->status == UserCenterStatus::USER_CERT_IS_PASS) {
            $this->error('已通过审核,不能修改');
        }

        $userData->name     = $params['name'];
        $userData->tel      = $params['tel'];
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
        $userMerge  = make(UserRepository::class)->getUserMerge($userid, ['u.dc', 'u.score', 'u.money', 'd.total']);
        $userIncome = $this->userService->getUserIncome($userid);
        return $this->success(array_merge($userMerge->toArray(), $userIncome));
    }

    /*
     * 资金记录
     */
    public function getMoneyLog(): ResponseInterface
    {
        $page     = $this->request->post('page', 1) ?: 1;
        $pageSize = $this->request->post('page_size', 10);
        $field    = ['w.*', 'u.nickname'];
        $data     = (make(UserRepository::class))->getMoneyLog(user()['id'], $page, $pageSize, $field);
        return $this->success($data);
    }

    /*
     * 获取共享分记录
     */
    public function getScoreLog(): ResponseInterface
    {
        $page     = $this->request->post('page', 1) ?: 1;
        $pageSize = $this->request->post('page_size', 10);
        $field    = ['w.*', 'u.nickname'];
        $data     = (make(UserRepository::class))->getScoreLog(user()['id'], $page, $pageSize, $field);
        return $this->success($data);
    }

    /*
     * 获取提现记录
     */
    public function getCashLog(): ResponseInterface
    {
        $page     = $this->request->post('page', 1) ?: 1;
        $pageSize = $this->request->post('page_size', 10);
        $field    = ['id', 'name', 'zhi', 'money', 'status', 'time'];
        $data     = (make(UserRepository::class))->getCashLog(user()['id'], $page, $pageSize, $field);
        return $this->success($data);
    }
}
