<?php


namespace App\Controller;

use App\Request\Utils;
use App\Model\Sms as SmsModel;

class Sms extends AbstractController
{
    /*
     * 发送短信
     */
    public function send(Utils $request)
    {
        $request->scene('sms')->validateResolved();
        $mobile      = $request->post('mobile');
        $event       = $request->post('event', 'verify');
        $ipSendTotal = SmsModel::where(['ip' => get_client_ip()])->whereTime('create_time', '-1 hours')->count();
        if ($ipSendTotal >= 5) {
            $this->error(__('发送频繁'));
        }
        //todo 完善SMS repo
    }
}