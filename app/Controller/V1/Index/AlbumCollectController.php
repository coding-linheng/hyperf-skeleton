<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Request\Album;
use App\Services\AlbumService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 收藏专辑以及专辑图片相关操作
 */

class AlbumCollectController extends AbstractController
{
    #[Inject]
    protected AlbumService $albumService;

    /**
     * 获取收藏该专辑的设计师列表.
     */
    public function getDesignerByCollectAlbum(): ResponseInterface
    {
      $id          = $this->request->input('id', 0);
      if (empty($id) ) {
        $this->response->error(ErrorCode::VALIDATE_FAIL, '缺少参数！');
      }
      return $this->response->success($this->albumService->getDesignerByCollectAlbum(intval($id)));
    }

    /**
     * 获取收藏该图片的设计师列表.
     */
    public function getDesignerByCollectImg(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        return $this->response->success($this->albumService->getDesignerByCollectImg(intval($id)));
    }

    /**
     * 收藏图片.
     * @param: id 收藏灵感图片的id
     * @param: type 操作类型，1收藏，2取消，默认不传表示收藏
     */
    public function collectAlbumImg(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $type        = $request->input('type', 1);
        $remark      = $request->path();
        $collectNum  = $this->albumService->collectAlbumImg(intval($id), intval($type), (string)$remark);
        return $this->success(['collect_num' => $collectNum]);
    }

    /**
     * 收藏专辑.
     */
    public function collectAlbum(): ResponseInterface
    {
        $id          = $this->request->input('id', 0);
        $type        = $this->request->input('type', 1);

        if (empty($id) || empty($type)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '缺少参数！');
        }
        return $this->response->success($this->albumService->collectAlbum(intval($id), intval($type)));
    }

    /**
     * 采集图片灵感图片.
     * 请求参数 cid 采集灵感图片的id
     * 请求参数 aid 采集到属于我的专辑的id
     * eg: /v1/album/captureAlbumImg?cid=1569692&aid=1
     * 返回该灵感图片原始图片，应该是加密后的原始图片.
     * {"code":0,"msg":"success","data":{"name":"ia_200001127.jpg","path":"http:\/\/qzdj3z3qz.hn-bkt.clouddn.com\/20210630\/3175jic1n2urpk8.jpg?e=1633766350&token=hl73g4h81_b6ysJCzQ_f_S49_0Ncu8C1mBJZHAje:mzL2-jquyhxZXjtomqUDxKV-tyk=","title":"二十四节气霜降地产海报"}}.
     */
    public function captureAlbumImg(Album $request): ResponseInterface
    {
        $request->scene('captureAlbumImg')->validateResolved();
        $aid   = $request->input('aid');
        $cid   = $request->input('cid');
        $title = $request->input('title', '');
        $list  = $this->albumService->captureAlbumImg(intval($cid), intval($aid), (string)$title);
        return $this->success($list);
    }
}
