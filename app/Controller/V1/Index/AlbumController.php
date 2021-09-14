<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

/*
 * 专辑以及专辑列表相关操作
 */

class AlbumController extends AbstractController {

  /**
   * 获取专辑列表.
   */
  public function getList(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 搜索关键字专辑列表.
   */
  public function searchList(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 获取搜索详情.
   */
  public function getDetail(): ResponseInterface {
    return $this->response->success();
  }




}
