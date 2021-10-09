<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Request\Album;
use App\Services\AlbumService;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
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
        return $this->response->success();
    }

    /**
     * 获取收藏该图片的设计师列表.
     */
    public function getDesignerByCollectImg(): ResponseInterface
    {
        return $this->response->success();
    }

    /**
     * 收藏图片.
     */
    public function collectAlbumImg(): ResponseInterface
    {
        return $this->response->success();
    }

    /**
     * 收藏专辑.
     */
    public function collectAlbum(): ResponseInterface
    {
        return $this->response->success();
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
        $list = $this->albumService->captureAlbumImg(intval($cid),intval($aid));
        return $this->success($list);
    }
}
