<?php

namespace App\Controller\V1\Pay;

use Payment\Common\PayException;
use Payment\Client\Charge;
use Payment\Config;

class AliPayController{



  //支付宝网页支付
  function webPayOrder(){

    $aliConfig = [
      'use_sandbox' => true, // 是否使用沙盒模式

      'app_id'    => '2016073100130857',
      'sign_type' => 'RSA2', // RSA  RSA2


      // 支付宝公钥字符串
      'ali_public_key' => '',

      // 自己生成的密钥字符串
      'rsa_private_key' => '',

      'limit_pay' => [
        //'balance',// 余额
        //'moneyFund',// 余额宝
        //'debitCardExpress',// 	借记卡快捷
        //'creditCard',//信用卡
        //'creditCardExpress',// 信用卡快捷
        //'creditCardCartoon',//信用卡卡通
        //'credit_group',// 信用支付类型（包含信用卡卡通、信用卡快捷、花呗、花呗分期）
      ], // 用户不可用指定渠道支付当有多个渠道时用“,”分隔

      // 与业务相关参数
      'notify_url' => 'https://dayutalk.cn/notify/ali',
      'return_url' => 'https://dayutalk.cn',
    ];


    date_default_timezone_set('Asia/Shanghai');
   // $aliConfig = require_once __DIR__ . '/../aliconfig.php';
    // 订单信息
    $orderNo = time() . rand(1000, 9999);
    $payData = [
      'body'    => 'ali web pay',
      'subject'    => '测试支付宝电脑网站支付',
      'order_no'    => $orderNo,
      'timeout_express' => time() + 600,// 表示必须 600s 内付款
      'amount'    => '0.01',// 单位为元 ,最小为0.01
      'return_param' => '123123',
      'client_ip' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '127.0.0.1',// 客户地址
      'goods_type' => '1',
      'store_id' => '',

      // 说明地址：https://doc.open.alipay.com/doc2/detail.htm?treeId=270&articleId=105901&docType=1
      // 建议什么也不填
      'qr_mod' => '',
    ];

    try {
      $url = Charge::run(Config::ALI_CHANNEL_WEB, $aliConfig, $payData);
    } catch (PayException $e) {
      echo $e->errorMessage();
      exit;
    }

    header('Location:' . $url);
  }

}

