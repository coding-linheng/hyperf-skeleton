<?php
namespace App\Controller\V1\Pay;
use Payment\Notify\PayNotifyInterface;
use Payment\Config;
use Payment\Common\PayException;
use Payment\Client\Notify;
// 自己实现一个类，继承该接口
class PayNotifyController implements PayNotifyInterface
{
  public function notifyProcess(array $data)
  {
    $channel = $data['channel'];
    if ($channel === Config::ALI_CHARGE) {// 支付宝支付
        echo "Ali pay";
    } elseif ($channel === Config::WX_CHARGE) {// 微信支付
      echo "wechat pay";
    } elseif ($channel === Config::CMB_CHARGE) {// 招商支付
      echo "CMB_CHARGE pay";
    } elseif ($channel === Config::CMB_BIND) {// 招商签约
      echo "CMB_BIND pay";
    } else {
      // 其它类型的通知
      echo "UnKnow pay";
    }
    //todo  执行业务逻辑，成功后返回true
    return true;
  }

  /**
   * 处理自己的业务逻辑，如更新交易状态、保存通知数据等等
   * @param string $channel 通知的渠道，如：支付宝、微信、招商
   * @param string $notifyType 通知的类型，如：支付、退款
   * @param string $notifyWay 通知的方式，如：异步 async，同步 sync
   * @param array $notifyData 通知的数据
   * @return bool
   */
  public function handle(
    string $channel,
    string $notifyType,
    string $notifyWay,
    array $notifyData
  ) {
    //var_dump($channel, $notifyType, $notifyWay, $notifyData);exit;
    return true;
  }

  //支付回调地址
  public function notify(){


    date_default_timezone_set('Asia/Shanghai');

    $aliConfig = require_once __DIR__ . '/aliconfig.php';
    $wxConfig = require_once __DIR__ . '/wxconfig.php';
    $cmbConfig = require_once __DIR__ . '/cmbconfig.php';

    $callback = new self();

    $type = 'cmb_charge';// xx_charge

    if (stripos($type, 'ali') !== false) {
      $config = $aliConfig;
    } elseif (stripos($type, 'wx') !== false) {
      $config = $wxConfig;
    } else {
      $config = $cmbConfig;
    }

    try {
      //$retData = Notify::getNotifyData($type, $config);// 获取第三方的原始数据，未进行签名检查

      $ret = Notify::run($type, $config, $callback);// 处理回调，内部进行了签名检查
    } catch (PayException $e) {
      echo $e->errorMessage();
      exit;
    }

    var_dump($ret);
    exit;
  }
}