<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Sms as SmsModel;
use App\Request\Utils;
use App\Services\SmsService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

class Sms extends AbstractController
{
    #[Inject]
    protected SmsService $smsService;

    /*
     * 发送短信
     */
    public function send(Utils $request): ResponseInterface
    {
        $request->scene('sms')->validateResolved();
        $mobile      = $request->post('mobile');
        $event       = $request->post('event', 'verify');
        $ipSendTotal = SmsModel::where(['ip' => get_client_ip()])->whereTime('create_time', '-1 hours')->count();

        if ($ipSendTotal >= 5) {
            $this->error(__('发送频繁'));
        }
        //检查手机号
        $this->smsService->checkMobile($mobile, $event);
        //发送短信
        $this->smsService->send($mobile, $event);
        return $this->success();
    }
}
