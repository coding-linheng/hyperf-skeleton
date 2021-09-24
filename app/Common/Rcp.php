<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Task\Producer\LoggerPlanProducer;
use Hyperf\Redis\RedisProxy;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * 处理一些风控逻辑.
 */
class Rcp
{

    public const RCP_USER = 'RCP_USER_Z'; //用户访问次数，后缀Z表示使用Zset

    public const RCP_IP = 'RCP_IP_Z'; //IP次数，使用Zset

    public const RCP_USER_AGENT = 'RCP_USER_AGENT_Z'; //USER_AGENT次数，使用Zset

    public const RCP_IP_URI = 'RCP_IP_URI_Z'; //URI次数，使用Zset

    public const RCP_USER_URI = 'RCP_USER_URI_Z'; //用户-uri 次数，使用Zset

    /* 黑名单  */

    public const RCP_DAY_BLACK_IP = 'RCP_DAY_BLACK_IP_Z'; //用户当天临时IP黑名单,连续3次进入该名单则被拉入永久封禁

    public const RCP_BLACK_IP = 'RCP_BLACK_IP_Z'; //永久用户IP黑名单

    public const RCP_DAY_BLACK_USER = 'RCP_DAY_BLACK_USER_Z'; //用户当天临时黑名单,连续3次进入该名单则被拉入永久封禁

    public const RCP_BLACK_USER = 'RCP_BLACK_USER_Z'; //永久用户黑名单

    /* 日志入库  */

    //请求参数日志列表，经过整理后入库，后续看日志量决定是否存入日志分析库
    public const RCP_LOG_DETAIL_LIST = 'RCP_USER_URI_Z';

    protected RedisProxy $redis;

    protected ServerRequestInterface $request;  //当前http请求

    protected array $user;   //当前用户，可以为空

    protected string $userCode;   //当前用户编码，唯一标记即可，默认使用id,可以为空

    protected array $configs;  //配置数组

    protected string $uri;

    protected string $ip;

    /********************* 基本风控 **************************************/

    protected int $RCP_USER_DEFAULT_LIMIT = 10000; //用户每天访问默认限制次数

    protected int $RCP_IP_DEFAULT_LIMIT = 10000; //每个IP每天访问默认限制次数

    protected int $RCP_IP_URI_DEFAULT_LIMIT = 2000; //每个IP每天访问某个具体URI默认限制次数

    protected int $RCP_USER_URI_DEFAULT_LIMIT = 500; // 用户每天访问某个具体URI默认限制次数

    public function __construct()
    {
        $this->redis = redis('rcp');
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
     *
     * @param  ServerRequestInterface  $request
     * @param  array                   $user
     *
     * @return bool
     * @throws \Exception
     */
    public function check(ServerRequestInterface $request, array $user): bool
    {
        try {
            $this->request = $request;
            $this->user = $user;
            $this->uri = $this->request->getUri()->getPath();
            $this->ip = get_client_ip();
            $this->userCode = strval($user['id'] ?? 0);

            //判断是否有IP，和uri
            if (empty($this->ip) || empty($this->uri)) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, "系统评估您为非法访问！");
            }

            //判断是否为接口，静态页面如果不是特殊资源不参与判断
            if ($this->isStatic()) {
                return true;
            }

            //记录日志
            $this->pushLogs();

            //判断是否属于黑名单，如果属于直接报错
            if (!$this->checkRcpBlackList()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, "您已被系统限制访问！");
            }

            //防CC，防爬虫等公用策略
            if (!$this->checkRcp()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, "您访问过于频繁！");
            }
            //定制风控策略
            if (!$this->checkSpecialRcp()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR);
            }
            return true;
        } catch (\Exception|Throwable $e) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, $e->getMessage());
        }
    }

    /**
     * 检查风控是否触发.公共策略部分检查.
     */
    private function isStatic()
    {
        //将uri 拆开如果包含v1则表示不是静态
        if (strpos($this->uri, '/v1') !== false) {
            return false;
        }
        return true;
    }

    /**
     * 检查风控是否触发.公共策略部分检查.
     */
    private function checkRcpBlackList(): bool
    {
        //用户当天临时IP黑名单,连续3次进入该名单则被拉入永久封禁
        $isDayBlackIp = $this->redis->zScore(self::RCP_DAY_BLACK_IP, $this->ip);

        if ($isDayBlackIp) {
            return false;
        }

        //永久用户IP黑名单
        $isBlackIp = $this->redis->zScore(self::RCP_BLACK_IP, $this->ip);

        if ($isBlackIp && $isBlackIp >= 3) {
            return false;
        }

        if (empty($this->userCode)) {
            return true;
        }

        //用户当天临时黑名单,连续3次进入该名单则被拉入永久封禁
        $isDayBlackUser = $this->redis->zScore(self::RCP_DAY_BLACK_USER, $this->userCode);

        if ($isDayBlackUser) {
            return false;
        }

        //永久用户黑名单
        $isBlackUser = $this->redis->zScore(self::RCP_BLACK_USER, $this->userCode);

        if ($isBlackUser && $isBlackUser >= 3) {
            return false;
        }

        return true;
    }

    /**
     * 检查风控是否触发.公共策略部分检查.
     */
    private function checkRcp(): bool
    {
        return $this->checkRcpByIp() && $this->checkRcpByUriIp() && $this->checkRcpByUser() && $this->checkRcpByUserUri();
    }

    /**
     * 检查风控是否触发.定制策略部分检查.
     */
    private function checkSpecialRcp(): bool
    {
        //是否配置定制策略

        //单个定制策略

        //组合策略，按着性能损耗优先级排序执行对应的函数，根据综合评分是否风控

        return true;
    }


    /**
     * 检查访Ip问该路径的风控是否触发.
     */
    private function checkRcpByUriIp(): bool
    {
        $this->RCP_IP_URI_DEFAULT_LIMIT = $this->configs['limit_count']['ip_uri_day_limit'] ??
            $this->RCP_IP_URI_DEFAULT_LIMIT;
        return $this->checkRcpByParams(self::RCP_IP_URI, $this->RCP_IP_URI_DEFAULT_LIMIT, $this->uri . '-' . $this->ip);
    }

    /**
     * 检查访问用户是否是否触发风控.
     */
    private function checkRcpByUser(): bool
    {
        if (empty($this->userCode)) {
            return true;
        }
        $this->RCP_USER_DEFAULT_LIMIT = $this->configs['limit_count']['user_day_limit'] ??
            $this->RCP_USER_DEFAULT_LIMIT;
        return $this->checkRcpByParams(self::RCP_USER, $this->RCP_USER_DEFAULT_LIMIT, $this->userCode);
    }

    /**
     * 检查访问用户-Uri是否是否触发风控.
     */
    private function checkRcpByUserUri(): bool
    {
        if (empty($this->userCode)) {
            return true;
        }
        $this->RCP_USER_URI_DEFAULT_LIMIT = $this->configs['limit_count']['user_uri_day_limit'] ??
            $this->RCP_USER_URI_DEFAULT_LIMIT;
        return $this->checkRcpByParams(
            self::RCP_USER_URI,
            $this->RCP_USER_URI_DEFAULT_LIMIT,
            $this->userCode . '-' . $this->uri
        );
    }

    /**
     * 检查访问Ip是否触发风控.
     */
    private function checkRcpByIp(): bool
    {
        $this->RCP_IP_DEFAULT_LIMIT = $this->configs['limit_count']['ip_day_limit'] ?? $this->RCP_IP_DEFAULT_LIMIT;
        return $this->checkRcpByParams(self::RCP_IP, $this->RCP_IP_DEFAULT_LIMIT, $this->ip);
    }

    /**
     * 检查访问该路径的风控是否触发.
     *
     * @param $key
     * @param $limit
     * @param $member
     *
     * @return bool
     */
    private function checkRcpByParams($key, $limit, $member): bool
    {
        //一分钟内访问次数
        if ($this->redis->exists($key . date('YmdHi'))) {
            //当前次数+1
            $mTimes = $this->redis->zIncrBy($key . date('YmdHi'), 1, $member);

            if ($mTimes >= intval($limit * 20 / (24 * 60))) {
                return false;
            }
        } else {
            //新增key
            $this->redis->zAdd($key . date('YmdHi'), 1, $member);
            //设置过期时间
            $this->redis->expire($key . date('YmdHi'), 60);
        }

        //检查一小时内访问次数
        if ($this->redis->exists($key . date('YmdH'))) {
            //当前次数+1
            $hTimes = $this->redis->zIncrBy($key . date('YmdH'), 1, $member);

            if ($hTimes >= intval($limit * 5 / (24))) {
                return false;
            }
        } else {
            //新增key
            $this->redis->zAdd($key . date('YmdH'), 1, $member);
            //设置过期时间
            $this->redis->expire($key . date('YmdH'), 3600);
        }

        //检查一天内访问次数
        if ($this->redis->exists($key . date('Ymd'))) {
            //当前次数+1
            $dayTimes = $this->redis->zIncrBy($key . date('Ymd'), 1, $member);
        } else {
            //新增key
            $dayTimes = 1;
            $this->redis->zAdd($key . date('Ymd'), 1, $member);
            //设置过期时间
            $this->redis->expire($key . date('Ymd'), 3600 * 24);
        }

        //判断类型，增加黑名单
        $blackKey = $blackDayKey = $blackMember = '';
        switch ($key) {
            case self::RCP_USER:
                $blackMember = $this->userCode;
                $blackKey    = self::RCP_DAY_BLACK_USER;
                $blackDayKey = self::RCP_BLACK_USER;
                break;
            case self::RCP_IP_URI:
            case self::RCP_IP:
                $blackMember = $this->ip;
                $blackKey    = self::RCP_DAY_BLACK_IP;
                $blackDayKey = self::RCP_BLACK_IP;
                break;
            case self::RCP_USER_URI:
                $blackMember = $member;
                $blackKey    = self::RCP_DAY_BLACK_USER;
                $blackDayKey = self::RCP_BLACK_USER;
                break;
            default:
                break;
        }

        if (empty($blackKey) || empty($blackDayKey)) {
            return true;
        }

        if ($dayTimes > $limit && !empty($blackMember)) {
            $this->redis->zIncrBy($blackDayKey, 1, $blackMember);
            $this->redis->zIncrBy($blackKey, 1, $blackMember);
            return false;
        }
        return true;
    }

    /**
     * 将Redis中统计的数据需要持久化的入库，保持Redis与数据库同步.
     */
    private function refreshRedisToMysql()
    {
        //将缓存中的黑名单同步入数据库

        //将缓存中的总/当天的访问次数同步入数据库，取俩者中的大者
    }

    /**
     * 详细日志入库，以备后续分析等.
     */
    private function pushLogs()
    {
        $loggerData = $this->formatLogParams();
        $loggerProducerTask = di()->get(LoggerPlanProducer::class);
        //将请求日志推入异步队列记录入库
        $loggerProducerTask->recordRequestLog($loggerData, 0);
    }

    /**
     *  格式化日志参数.
     */
    private function formatLogParams(): array
    {
        return [
               "ip"=>$this->ip,
               "user_code"=>$this->userCode,
               "uri"=>$this->uri,
               "refer"=>$this->request->getHeader("Refer"),
               "user_agent"=>$this->request->getHeader("User-Agent"),
               "request_params"=>$this->request->getQueryParams(),
               "request_method"=>$this->request->getMethod(),
               "create_time"=>time(),
            ];
    }

}
