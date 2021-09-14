<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use Psr\Http\Message\ResponseInterface;

/*
 * 收藏专辑以及专辑图片相关操作
 */

class AlbumCollectController extends AbstractController {

  /**
   * 获取收藏该专辑的设计师列表.
   *
   */
  public function getDesignerByCollectAlbum(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 获取收藏该图片的设计师列表.
   *
   */
  public function getDesignerByCollectImg(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 收藏图片.
   *
   */
  public function collectAlbumImg(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 收藏专辑.
   *
   */
  public function collectAlbum(): ResponseInterface {
    return $this->response->success();
  }


}
