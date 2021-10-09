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
 * 专辑以及专辑列表相关操作
 */

class AlbumController extends AbstractController
{
    #[Inject]
    protected AlbumService $albumService;

    /**
     * 获取随机专辑灵感图片推荐展示列表.
     */
    public function getRandList(): ResponseInterface
    {
        $data = $this->albumService->getListPageRand([]);
        return $this->response->success($data);
    }

    /**
     * 搜索关键字专辑列表.
     * query 查询关键字选填，不填为全部
     * order 排序字段：最新采集 dtime，最新更新 g_time，上周最高采集 caiji
     * labels 标签筛选 可选.
     */
    public function searchList(): ResponseInterface
    {
        $queryString = $this->request->input('query', '');
        $labels      = $this->request->input('labels', '');

        if (!empty($labels)) {
            $queryString .= $queryString . ' ' . $labels;
        }
        $order = $this->request->input('order', '');

        if (!empty($order) && !in_array($order, ['dtime', 'g_time', 'caiji'])) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '暂不支持的排序筛选');
        }
        $list = $this->albumService->searchAlbumList($queryString, $order);
        return $this->response->success($list);
    }

    /**
     * 获取灵感详情.
     * 返回该灵感图片对应的详细信息以及专辑列表.
     */
    public function getDetail(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id   = $request->input('id');
        $list = $this->albumService->getDetail(intval($id));
        return $this->success($list);
    }

    /**
     * 获取灵感图片对应的原创作者信息.
     */
    public function getAlbumAuthor(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id   = $request->input('id');
        $list = $this->albumService->getAlbumAuthor(intval($id));
        return $this->success($list);
    }

    /**
     * 获取灵感原图详情.
     * 返回该灵感图片原始图片，应该是加密后的原始图片.
     */
    public function getOriginAlbumPic(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id   = $request->input('id');
        $list = $this->albumService->getOriginAlbumPic(intval($id));
        return $this->success($list);
    }
}
