<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Redis\RedisProxy;
use phpseclib3\Math\BigInteger;
use Psr\Http\Message\ServerRequestInterface;


/**
 * 处理一些风控逻辑.
 */
class Rcp
{
    protected RedisProxy $redis;

    protected ServerRequestInterface $request;  //当前http请求

    protected array $user;   //当前用户，可以为空

    protected BigInteger $userCode;   //当前用户编码，唯一标记即可，默认使用id,可以为空

    protected array $configs;  //配置数组

    protected string $uri;

    protected string $ip;

   /********************* 基本风控 **************************************/

    protected int $RCP_USER_DEFAULT_LIMIT=10000; //用户每天访问默认限制次数

    protected int $RCP_IP_DEFAULT_LIMIT=10000; //每个IP每天访问默认限制次数

    protected int $RCP_IP_URI_DEFAULT_LIMIT=2000; //每个IP每天访问某个具体URI默认限制次数

    protected int $RCP_USER_URI_DEFAULT_LIMIT=500; // 用户每天访问某个具体URI默认限制次数

    const RCP_USER="RCP_USER_Z"; //用户访问次数，后缀Z表示使用Zset

    const RCP_IP="RCP_IP_Z"; //IP次数，使用Zset

    const RCP_USER_AGENT="RCP_USER_AGENT_Z"; //USER_AGENT次数，使用Zset

    const RCP_IP_URI="RCP_IP_URI_Z"; //URI次数，使用Zset

    const RCP_USER_URI="RCP_USER_URI_Z"; //用户-uri 次数，使用Zset

   /********************* 黑名单  **************************************/

    const RCP_DAY_BLACK_IP="RCP_DAY_BLACK_IP_Z"; //用户当天临时IP黑名单,连续3次进入该名单则被拉入永久封禁

    const RCP_BLACK_IP="RCP_BLACK_IP_Z"; //永久用户IP黑名单

    const RCP_DAY_BLACK_USER="RCP_DAY_BLACK_IP_Z"; //用户当天临时黑名单,连续3次进入该名单则被拉入永久封禁

    const RCP_BLACK_USER="RCP_BLACK_IP_Z"; //永久用户黑名单

   /********************* 日志入库  **************************************/

    //请求参数日志列表，经过整理后入库，后续看日志量决定是否存入日志分析库
    const RCP_LOG_DETAIL_LIST="RCP_USER_URI_Z";

    public function __construct()
    {
        $this->redis = redis("rcp");
        //加载风控配置json
        $res = $this->initRcpConfig();

        if (!$res) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '初始化配置失败！');
        }
    }

    /**
     * 克隆方法私有化，防止复制实例.
     */
    private function __clone()
    {
    }

    /**
     * 初始化配置，清除redis配置缓存，从数据库加载对应的策略放入redis里面.
     */
    public function initRcpConfig(): bool
    {
        return true;
    }

    /**
     * 检查是否触发现有风控规则和模型.
     */
    public function check(ServerRequestInterface $request, array $user): bool
    {
        $this->request = $request;
        $this->user    = $user;
        $this->uri = $this->request->getUri()->getPath();
        $this->ip = get_client_ip();
        $this->userCode=$user['id']??0;

        //判断是否属于黑名单，如果属于直接报错
        if (!$this->checkRcpBlackList()) {
          throw new BusinessException(ErrorCode::SERVER_RCP_ERROR,"您已限制访问");
        }

        //防CC，防爬虫等公用策略
        if (!$this->checkRcp()) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR,"您已暂时限制部分访问");
        }
        //定制风控策略
        if ($this->checkSpecialRcp()) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR);
        }
        return true;
    }

    /**
     * 检查风控是否触发.公共策略部分检查.
     */
    private function checkRcpBlackList(): bool
    {

      //用户当天临时IP黑名单,连续3次进入该名单则被拉入永久封禁
      $isDayBlackIp= $this->redis->zScore(self::RCP_DAY_BLACK_IP,$this->ip);
      if($isDayBlackIp){
        return false;
      }

      //永久用户IP黑名单
      $isBlackIp= $this->redis->zScore(self::RCP_BLACK_IP,$this->ip);
      if($isBlackIp && $isBlackIp>=3){
        return false;
      }

      if(empty($this->userCode)){
        return true;
      }

      //用户当天临时黑名单,连续3次进入该名单则被拉入永久封禁
      $isDayBlackUser= $this->redis->zScore(self::RCP_DAY_BLACK_USER,$this->userCode);
      if($isDayBlackUser){
        return false;
      }

      //永久用户黑名单
      $isBlackUser= $this->redis->zScore(self::RCP_BLACK_USER,$this->userCode);
      if($isBlackUser && $isBlackUser>=3){
        return false;
      }

      return true;
    }

    /**
     * 检查风控是否触发.公共策略部分检查.
     */
    private function checkRcp(): bool
    {
        return $this->checkRcpByIp() && $this->checkRcpByUri() && $this->checkRcpByUser();
    }

    /**
     * 检查风控是否触发.定制策略部分检查.
     */
    private function checkSpecialRcp(): bool
    {
        //是否配置定制策略

        //单个定制策略

        //组合策略，按着性能损耗优先级排序执行对应的函数，根据综合评分是否风控

        return $this->checkRcpByIp();
    }

    /**
     * 检查访问该路径的风控是否触发.
     */
    private function checkRcpByUri(): bool
    {
        $this->RCP_IP_URI_DEFAULT_LIMIT=$this->configs["limit_count"]["uri_day_limit"]??$this->RCP_IP_URI_DEFAULT_LIMIT;

        //检查当前ip，账号一分钟内访问该Url次数
        if($this->redis->exists(self::RCP_IP_URI.date("YmdHi"))){
          //当前次数+1
          $mTimes= $this->redis->zIncrBy(self::RCP_IP_URI.date("YmdHi"),1,$this->uri."-".$this->ip);
          if($mTimes>=intval($this->RCP_IP_URI_DEFAULT_LIMIT*20/(24*60))){
             return false;
          }
        }else{
          //新增key
          $this->redis->zAdd(self::RCP_IP_URI.date("YmdHi"),1,$this->uri."-".$this->ip);
          //设置过期时间
          $this->redis->expire(self::RCP_IP_URI.date("YmdHi"),60);
        }

        //检查当前ip，账号一小时内访问该Url次数
        if($this->redis->exists(self::RCP_IP_URI.date("YmdH"))){
          //当前次数+1
          $hTimes=$this->redis->zIncrBy(self::RCP_IP_URI.date("YmdH"),1,$this->uri."-".$this->ip);
          if($hTimes>=intval($this->RCP_IP_URI_DEFAULT_LIMIT*5/(24))){
            return false;
          }
        }else{
          //新增key
          $this->redis->zAdd(self::RCP_IP_URI.date("YmdH"),1,$this->uri."-".$this->ip);
          //设置过期时间
          $this->redis->expire(self::RCP_IP_URI.date("YmdH"),3600);
        }

        //检查当前ip，账号一天内访问该Url次数
        if($this->redis->exists(self::RCP_IP_URI.date("Ymd"))){
          //当前次数+1
          $dayTimes = $this->redis->zIncrBy(self::RCP_IP_URI.date("Ymd"),1,$this->uri."-".$this->ip);
        }else{
          //新增key
          $dayTimes =1;
          $this->redis->zAdd(self::RCP_IP_URI.date("Ymd"),1,$this->uri."-".$this->ip);
          //设置过期时间
          $this->redis->expire(self::RCP_IP_URI.date("Ymd"),3600*24);
        }
        if($dayTimes>$this->RCP_IP_URI_DEFAULT_LIMIT){
          $this->redis->zIncrBy(self::RCP_DAY_BLACK_IP,1,$this->ip);
          $this->redis->zIncrBy(self::RCP_BLACK_IP,1,$this->ip);
        }

        return true;
    }

    /**
     * 检查访问用户是否是否触发风控.
     */
    private function checkRcpByUser(): bool
    {
        //检查当前用户一分钟内访问次数
        //检查当前用户一小时内访问次数
        //检查当前用户一天内访问次数
        //检查当前用户总访问次数
        return true;
    }

    /**
     * 检查访问Ip是否触发风控.
     */
    private function checkRcpByIp(): bool
    {
        //检查当前ip一分钟内访问次数
        //检查当前ip一小时内访问次数
        //检查当前ip一天内访问次数
        //检查当前ip总访问次数
        return true;
    }

    /**
     * 将Redis中统计的数据需要持久化的入库，保持Redis与数据库同步
     */
    private function refreshRedisToMysql(){

    }



}
