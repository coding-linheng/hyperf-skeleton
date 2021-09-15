<?php
namespace App\Controller\V1\Pay;
use Payment\Common\PayException;
use Payment\Client\Charge;
use Payment\Config;

class WxPayController{
  //微信网页支付
  function webPayOrder(){
    date_default_timezone_set('Asia/Shanghai');

    //$wxConfig = require_once __DIR__ . '/../wxconfig.php';
    $wxConfig = [
      'use_sandbox' => false, // 是否使用 微信支付仿真测试系统

      'app_id'       => 'wxxxxxxxx',  // 公众账号ID
      'sub_appid'    => 'wxxxxxxxx',  // 公众子商户账号ID
      'mch_id'       => '123123123', // 商户id
      'sub_mch_id'   => '123123123', // 子商户id
      'md5_key'      => '23423423dsaddasdas', // md5 秘钥
      'app_cert_pem' => 'apiclient_cert.pem',
      'app_key_pem'  => 'apiclient_key.pem',
      'sign_type'    => 'MD5', // MD5  HMAC-SHA256
      'limit_pay'    => [
        //'no_credit',
      ], // 指定不能使用信用卡支付   不传入，则均可使用
      'fee_type' => 'CNY', // 货币类型  当前仅支持该字段

      'notify_url' => 'https://dayutalk.cn/v1/notify/wx',

      'redirect_url' => 'https://dayutalk.cn/', // 如果是h5支付，可以设置该值，返回到指定页面
    ];


    $orderNo = time() . rand(1000, 9999);
    // 订单信息
    $payData = [
      'body'    => 'test body',
      'subject'    => 'test subject',
      'order_no'    => $orderNo,
      'timeout_express' => time() + 600,// 表示必须 600s 内付款
      'amount'    => '3.01',// 微信沙箱模式，需要金额固定为3.01
      'return_param' => '123',
      'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
      'openid' => 'ottkCuO1PW1Dnh6PWFffNk-2MPbY',
      'product_id' => '123',

      // 如果是服务商，请提供以下参数
      'sub_appid' => '',//微信分配的子商户公众账号ID
      'sub_mch_id' => '',// 微信支付分配的子商户号
    ];

    try {
      $ret = Charge::run(Config::WX_CHANNEL_QR, $wxConfig, $payData);
    } catch (PayException $e) {
      echo $e->errorMessage();
      exit;
    }

    echo $ret;
  }
}