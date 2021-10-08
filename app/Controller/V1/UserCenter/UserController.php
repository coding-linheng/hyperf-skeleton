<?php

declare(strict_types=1);

namespace App\Controller\V1\UserCenter;

use App\Common\Sms;
use App\Constants\UserCenterStatus;
use App\Controller\AbstractController;
use App\Model\Noticelook;
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
        return $this->success($this->userService->getUserMerge(user()['id'], $field));
    }

    /*
     * 绑定手机号
     */
    public function bindMobile(User $request): ResponseInterface
    {
        $request->scene('bind_mobile')->validateResolved();
        $mobile  = $request->post('mobile');
        $captcha = $request->post('captcha');
        $this->smsService->check($mobile, $captcha);

        $user         = $this->userService->getUser(user()['id']);
        $user->mobile = $mobile;
        $user->save();
        Sms::flush($mobile);
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
        $params['status'] = UserCenterStatus::USER_CERT_IS_SUBMIT;
        $userData->fill($params)->save();
        return $this->success();
    }

    /**
     * 上传头像.
     */
    public function uploadHeadImg(User $request): ResponseInterface
    {
        $request->scene('upload_head')->validateResolved();
        $user = $this->userService->getUser(user()['id']);

        $user->imghead = $request->input('head_image');
        $user->save();
        return $this->success();
    }

    /*
     * 获取动态
     */
    public function getMoving(): ResponseInterface
    {
        $query = $this->request->all();
        $field = ['w.id', 'w.cid', 'w.time', 'w.type', 'w.uid', 'u.nickname', 'u.imghead'];
        $data  = $this->userService->getMoving(user()['id'], $query, $field);
        return $this->success($data);
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

    /*
     * 资金记录
     */
    public function getMoneyLog(): ResponseInterface
    {
        $query = $this->request->all();
        $field = ['w.*', 'u.nickname'];
        $data  = $this->userService->getMoneyLog(user()['id'], $query, $field);
        return $this->success($data);
    }

    /*
     * 获取共享分记录
     */
    public function getScoreLog(): ResponseInterface
    {
        $query = $this->request->all();
        $field = ['w.*', 'u.nickname'];
        $data  = $this->userService->getScoreLog(user()['id'], $query, $field);
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
        $data     = $this->userService->getCashLog(user()['id'], $page, $pageSize, $field);
        return $this->success($data);
    }

    /*
     * 获取私信
     */
    public function getPrivateMessage(): ResponseInterface
    {
        $query = $this->request->all();
        $data  = $this->userService->getPrivateMessage(user()['id'], $query);
        return $this->success($data);
    }

    /*
     * 获取系统公告
     */
    public function getSystemMessage(): ResponseInterface
    {
        $query = $this->request->all();
        $data  = $this->userService->getSystemMessage(user()['id'], $query);
        return $this->success($data);
    }

    /*
     * 获取公告详情
     */
    public function getMessageDetail(User $request): ResponseInterface
    {
        $request->scene('notice')->validateResolved();
        $id   = (int) $request->input('notice_id');
        $data = $this->userService->getMessageDetail($id);
        Noticelook::updateOrCreate(['uid' => user()['id'], 'nid' => $id]);
        return $this->success($data);
    }

    /*
     * 提现
     */
    public function cash(): ResponseInterface
    {
        $money = $this->request->post('money');
        $this->userService->cash(user()['id'], $money);
        return $this->success();
    }
}
