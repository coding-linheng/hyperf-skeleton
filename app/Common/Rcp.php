<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Redis\RedisProxy;
use Psr\Http\Message\ServerRequestInterface;


/**
 * 处理一些风控逻辑.
 */
class Rcp
{
    protected RedisProxy $redis;

    protected ServerRequestInterface $request;  //当前http请求

    protected array $user;   //当前用户，可以为空

    protected array $configs;  //配置数组

    const RCP_USER="RCP_USER_Z"; //用户访问次数，后缀Z表示使用Zset
    const RCP_IP="RCP_IP_Z"; //IP次数，使用Zset
    const RCP_URI="RCP_IP_Z"; //IP次数，使用Zset
    const RCP_USER_URI="RCP_USER_URI_Z"; //用户-uri 次数，使用Zset




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

        //防CC，防爬虫等公用策略
        if (!$this->checkRcp()) {
            throw new BusinessException(ErrorCode::SERVER_RCP_ERROR);
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
        //检查当前ip，账号一分钟内访问该Url次数
        //检查当前ip，账号一小时内访问该Url次数
        //检查当前ip，账号一天内访问该Url次数
        //检查当前ip，账号总访问该Url次数

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
