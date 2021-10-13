<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Request\Wenku;
use App\Services\WenkuService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 文库相关操作
 */

class WenkuController extends AbstractController
{
    #[Inject]
    protected WenkuService $wenkuService;

    /**
     * 文库列表，支持搜索筛选.
     * query 搜素关键字，热门搜索，不填为全部
     * order 排序字段：默认 g_time, 最新 id，热门 downnum
     * lid 不传该字段或者传0则表示默认全部，1共享，2原创, mulu_id：分类id.
     * page 当前页数
     * page_size 一页条数，可以不传.
     */
    public function getList(Wenku $request): ResponseInterface
    {
        $request->scene('list')->validateResolved();
        $query['order']           = $request->input('order', '');
        $query['query']           = $request->input('query', '');
        $query['lid']             = $request->input('lid', 0);
        $query['mulu_id']         = $request->input('mulu_id', 0);
        $query['page']            = $request->input('page', 1);
        $query['page_size']       = $request->input('page_size', 20);
        $list                     = $this->wenkuService->getList($query);
        return $this->success($list);
    }

    /**
     * 文库详情页.
     * @param: id 文库的id
     */
    public function getDetail(Wenku $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $list        = $this->wenkuService->getDetail(intval($id));
        return $this->success($list);
    }

    /**
     * 文库详情页--相关推荐.
     * @param: id 文库的id
     */
    public function recommendList(Wenku $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        // $id                       = $request->input('id');
        $query['page']            = $request->input('page', 1);
        $query['page_size']       = $request->input('page_size', 20);
        $list                     = $this->wenkuService->recommendList($query);
        return $this->success($list);
    }

    /**
     * 文库详情页--作者其他.
     * @param: id 素材的id
     */
    public function getListByAuthor(Wenku $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id                       = $request->input('id');
        $query['page']            = $request->input('page', 1);
        $query['page_size']       = $request->input('page_size', 20);
        $list                     = $this->wenkuService->getListByAuthor(intval($id), $query);
        return $this->success($list);
    }
}
