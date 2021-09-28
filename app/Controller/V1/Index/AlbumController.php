<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
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
     * 获取专辑列表.
     */
    public function getList(): ResponseInterface
    {
        $data = $this->albumService->getListPageRand([]);
        return $this->response->success($data);
    }

    /**
     * 搜索关键字专辑列表.
     * query 查询关键字选填，不填为全部
     * order 排序字段：最新采集 dtime，最新更新 g_time，上周最高采集 last_caiji
     * labels 标签筛选 可选
     */
    public function searchList(): ResponseInterface
    {
        $queryString = $this->request->input('query', '');
        $labels = $this->request->input('labels', '');
        if(!empty($labels)){
            $queryString.= $queryString." ".$labels;
        }
        $order= $this->request->input('order', 'dtime');
        $list        = $this->albumService->searchAlbumList($queryString,$order);
        return $this->response->success($list);
    }

    /**
     * 获取搜索详情.
     */
    public function getDetail(): ResponseInterface
    {
        return $this->response->success();
    }
}
