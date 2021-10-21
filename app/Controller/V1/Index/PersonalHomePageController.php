<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Request\Album;
use App\Services\AlbumService;
use App\Services\PersonalHomePageService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 收藏专辑以及专辑图片相关操作
 */

class PersonalHomePageController extends AbstractController {

  #[Inject]
  protected AlbumService $albumService;

  #[Inject]
  protected PersonalHomePageService $personalPageService;

  /**
   * 个人主页统计信息.
   */
  public function homePage(): ResponseInterface {
    $uid=$this->request->input('uid',0);
    if(empty($uid)){
      $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
    }
    return $this->success($this->personalPageService->homePage($uid));

  }

  /**
   * 获取某个用户的列表粉丝列表.
   */
  public function fansListByUid(): ResponseInterface {
    $uid=$this->request->input('uid',0);
    if(empty($uid)){
      $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
    }
    return $this->success($this->personalPageService->fansListByUid($uid));

  }

  /**
   * 获取收藏该专辑的设计师列表.
   */
  public function getDesignerByCollectAlbum(): ResponseInterface {
    return $this->response->success();
  }

  /**
   * 获取收藏该图片的设计师列表.
   */
  public function getDesignerByCollectImg(): ResponseInterface {
    return $this->response->success();
  }

}
