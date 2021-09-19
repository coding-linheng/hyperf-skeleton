<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\Redis\RedisProxy;
use Psr\Http\Message\ServerRequestInterface;

/**
 * 处理一些风控逻辑
 */
class Rcp {

  protected RedisProxy $redis;
  protected ServerRequestInterface $request;
  protected array  $user;

  public function __construct(ServerRequestInterface $request, array $user)
  {
    $this->request = $request;
    $this->user    = $user;
    $this->redis   =  redis();
  }

  /**
   * 检查是否触发现有风控规则和模型
   */
  public function check(): bool {
    if (!$this->checkRcp()){
      throw new BusinessException(ErrorCode::SERVER_RCP_ERROR);
    }
    return TRUE;
  }

  /**
   * 检查风控是否触发
   */
  public function checkRcp(): bool{

    return $this->checkRcp() && $this->checkRcpByIp() && $this->checkRcpByUri() && $this->checkRcpByUser();

  }

  /**
   * 检查访问该路径的风控是否触发
   */
  public function checkRcpByUri(): bool{
      return TRUE;
  }

  /**
   * 检查访问用户是否是否触发风控
   */
  public function checkRcpByUser(): bool{
    return TRUE;
  }

  /**
   * 检查访问Ip是否触发风控
   */
  public function checkRcpByIp(): bool{
    return TRUE;
  }


}