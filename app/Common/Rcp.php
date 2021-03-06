<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Task\Producer\LoggerPlanProducer;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * 处理一些风控逻辑.
 */
class Rcp
{
    public const RCP_LIMIT_RATE_LOCK = 'RCP_LIMIT_RATE_LOCK'; //用户访问次数，后缀Z表示使用Zset

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

    #[Inject]
    protected RcpRedis $redis;

    protected ServerRequestInterface $request;  //当前http请求

    protected array $user;   //当前用户，可以为空

    protected string $userCode;   //当前用户编码，唯一标记即可，默认使用id,可以为空

    protected array $configs;  //配置数组

    protected string $uri;

    protected string $ip;

    /* 基本风控 */

    protected int $RCP_USER_DEFAULT_LIMIT = 10000; //用户每天访问默认限制次数

    protected int $RCP_IP_DEFAULT_LIMIT = 10000; //每个IP每天访问默认限制次数

    protected int $RCP_IP_URI_DEFAULT_LIMIT = 2000; //每个IP每天访问某个具体URI默认限制次数

    protected int $RCP_USER_URI_DEFAULT_LIMIT = 500; // 用户每天访问某个具体URI默认限制次数

    /* 限流 */
    protected int $RCP_LIMIT_RATE_COUNT = 1000; //访问限流

    protected int $RCP_LIMIT_RATE_IP_COUNT = 10; //Ip 限流

    public function __construct()
    {
        //加载风控配置json
        $res = $this->initRcpConfig();

        if (!$res) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '初始化配置失败！');
        }
    }

    //
    //    /**
    //     * 克隆方法私有化，防止复制实例.
    //     */
    //    private function __clone()
    //    {
    //    }

    /**
     * 初始化配置，清除redis配置缓存，从数据库加载对应的策略放入redis里面.
     */
    public function initRcpConfig(): bool
    {
        $rpcConfig = config('rcp.default', '');

        if (!empty($rpcConfig) && is_array($rpcConfig)) {
            $this->configs = $rpcConfig;
            return true;
        }

        return false;
    }

    /**
     * 检查是否触发现有风控规则和模型.
     *
     * @throws \Exception
     */
    public function check(ServerRequestInterface $request, array $user): bool
    {
        try {
            $this->request  = $request;
            $this->user     = $user;
            $this->uri      = $this->request->getUri()->getPath();
            $this->ip       = get_client_ip();
            $this->userCode = strval($user['id'] ?? 0);

            //判断是否有IP，和uri
            if (empty($this->ip) || empty($this->uri)) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '系统评估您为非法访问！');
            }

            //判断是否为接口，静态页面如果不是特殊资源不参与判断
            if ($this->isStatic()) {
                return true;
            }

            //表示没有开启风控
            if (!isset($this->configs['open']) || $this->configs['open'] != 1) {
                return true;
            }

            //原子性，一个IP请求同一个接口一秒钟内不能超过1个
            if ($this->doubleRequest()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '您访问频率太快！');
            }

            //消峰限流，最大并发值
            if ($this->limitRate()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '目前访问人数过多，排队中，请稍后重试！');
            }

            //记录日志
            $this->pushLogs();

            //判断是否属于黑名单，如果属于直接报错
            if (!$this->checkRcpBlackList()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '您已被系统限制访问！');
            }

            //防CC，防爬虫等公用策略
            if (!$this->checkRcp()) {
                throw new BusinessException(ErrorCode::SERVER_RCP_ERROR, '您访问过于频繁！');
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
     * 获取风控统计数据 top10.
     */
    public function getRcpStatics(): array
    {
        //ip
        $retArr['black_ip']       = $this->redis->zRevRange(self::RCP_BLACK_IP, 0, 10, true);
        $retArr['day_black_ip']   = $this->redis->zRevRange(self::RCP_DAY_BLACK_IP, 0, 10, true);
        $retArr['rcp_ip_uri']     = $this->redis->zRevRange(self::RCP_IP_URI, 0, 10, true);
        $retArr['rcp_ip']         = $this->redis->zRevRange(self::RCP_IP, 0, 10, true);
        $retArr['day_rcp_ip']     = $this->redis->zRevRange(self::RCP_IP . date('Ymd'), 0, 10, true);
        $retArr['day_rcp_ip_uri'] = $this->redis->zRevRange(self::RCP_IP_URI . date('Ymd'), 0, 10, true);
        //用户
        $retArr['black_user']       = $this->redis->zRevRange(self::RCP_BLACK_USER, 0, 10, true);
        $retArr['day_black_user']   = $this->redis->zRevRange(self::RCP_DAY_BLACK_USER, 0, 10, true);
        $retArr['rcp_user']         = $this->redis->zRevRange(self::RCP_USER, 0, 10, true);
        $retArr['rcp_user_uri']     = $this->redis->zRevRange(self::RCP_USER_URI, 0, 10, true);
        $retArr['day_rcp_user_uri'] = $this->redis->zRevRange(self::RCP_USER_URI . date('Ymd'), 0, 10, true);
        $retArr['day_rcp_user']     = $this->redis->zRevRange(self::RCP_USER . date('Ymd'), 0, 10, true);

        return $retArr;
    }

    /**
     * 获取最近7天风控统计数据.
     */
    public function getDayRcpStatics()
    {
        $returnArr = [];
        //将缓存中的黑名单同步入数据库
        for ($i = 0; $i < 7; ++$i) {
            $day                      = date('Ymd', strtotime("-{$i} day"));
            $tmpArr['day_rcp_ip']     = $this->redis->zRevRange(self::RCP_IP . $day, 0, 10, true);
            $tmpArr['day_rcp_ip_uri'] = $this->redis->zRevRange(self::RCP_IP_URI . $day, 0, 10, true);
            //用户
            $tmpArr['day_rcp_user_uri'] = $this->redis->zRevRange(self::RCP_USER_URI . $day, 0, 10, true);
            $tmpArr['day_rcp_user']     = $this->redis->zRevRange(self::RCP_USER . $day, 0, 10, true);
            $returnArr[$day]            = $tmpArr;
        }
        return $returnArr;
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
     * 防止一秒钟多个重复请求.
     */
    private function doubleRequest()
    {
        $key = md5($this->ip . $this->uri . $this->request->getMethod() . serialize($this->request->getQueryParams()));
        //如果存在则设置
        if ($this->redis->setnx($key, 1)) {
            //设置成功则表示不存在可以通过，设置过期时间
            $this->redis->expire($key, 1);
            return false;
        }
        return true;
    }

    /**
     * 高峰限流，削峰.
     */
    private function limitRate()
    {
        //RCP_LIMIT_RATE
        $this->RCP_LIMIT_RATE_COUNT = $this->configs['common_limit_count']['limit_rate_count'] ?? $this->RCP_LIMIT_RATE_COUNT;

        $limitCount = $this->redis->incr(self::RCP_LIMIT_RATE_LOCK);

        if ($limitCount == 1) {
            $this->redis->expire(self::RCP_LIMIT_RATE_LOCK, 2);
        }

        if ($limitCount > $this->RCP_LIMIT_RATE_COUNT * 2) {
            return true;
        }

        if ($limitCount > $this->RCP_LIMIT_RATE_COUNT) {
            $this->redis->expire(self::RCP_LIMIT_RATE_LOCK, 2);
        }

        //限制单个IP请求

        $this->RCP_LIMIT_RATE_IP_COUNT = $this->configs['common_limit_count']['limit_rate_ip_count'] ?? $this->RCP_LIMIT_RATE_IP_COUNT;

        $limitCountByIp = $this->redis->incr(self::RCP_LIMIT_RATE_LOCK . $this->ip);

        if ($limitCountByIp == 1) {
            $this->redis->expire(self::RCP_LIMIT_RATE_LOCK . $this->ip, 2);
        }

        if ($limitCountByIp > $this->RCP_LIMIT_RATE_IP_COUNT * 2) {
            return true;
        }

        if ($limitCountByIp > $this->RCP_LIMIT_RATE_IP_COUNT) {
            $this->redis->expire(self::RCP_LIMIT_RATE_LOCK . $this->ip, 2);
        }
        return false;
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
        return $this->checkRcpByIp() && $this->checkRcpByUriIp() && $this->checkRcpByUser()
                                     && $this->checkRcpByUserUri();
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
        $this->RCP_IP_URI_DEFAULT_LIMIT = $this->configs['common_limit_count']['ip_uri_day_limit'] ?? $this->RCP_IP_URI_DEFAULT_LIMIT;
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
        $this->RCP_USER_DEFAULT_LIMIT = $this->configs['common_limit_count']['user_day_limit'] ??
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
        $this->RCP_USER_URI_DEFAULT_LIMIT = $this->configs['common_limit_count']['user_uri_day_limit'] ??
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
        $this->RCP_IP_DEFAULT_LIMIT = $this->configs['common_limit_count']['ip_day_limit'] ?? $this->RCP_IP_DEFAULT_LIMIT;
        return $this->checkRcpByParams(self::RCP_IP, $this->RCP_IP_DEFAULT_LIMIT, $this->ip);
    }

    /**
     * 检查访问该路径的风控是否触发.
     *
     * @param $key
     * @param $limit
     * @param $member
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
     * todo() 这个部分最后做，目前不考虑，全部在Redis里面
     * 将Redis中统计的数据需要持久化的入库，保持Redis与数据库同步.
     */
    private function refreshRcpData(): bool
    {
        //将缓存中的黑名单同步入数据库

        //查询当前黑名单数据，判断数据库中是否存在，存在则更新

        //将缓存中的总/当天的访问次数同步入数据库，取俩者中的大者

        //同步最新数据库中数据放入redis

        return true;
    }

    /**
     * 详细日志入库，以备后续分析等.
     */
    private function pushLogs()
    {
        $loggerData         = $this->formatLogParams();
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
            'ip'             => $this->ip,
            'user_code'      => $this->userCode,
            'uri'            => $this->uri,
            'refer'          => $this->request->getHeader('Refer'),
            'user_agent'     => $this->request->getHeader('User-Agent'),
            'request_params' => $this->request->getQueryParams(),
            'request_method' => $this->request->getMethod(),
            'create_time'    => time(),
        ];
    }
}
