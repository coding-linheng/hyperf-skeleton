<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Request\Sucai;
use App\Services\SucaiService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 素材相关操作
 */

class SucaiController extends AbstractController
{
    #[Inject]
    protected SucaiService $sucaiService;


    /**
     * 素材搜索展示页面.
     * query 搜素关键字，热门搜索，不填为全部
     * order 排序字段：默认 g_time, 最新 id，热门 downnum
     * labels 标签筛选 可选.
     * lid 不传该字段或者传0则表示默认全部，1共享，2原创, mulu_id：分类,新版取消
     *
     * @param  Sucai  $request
     *
     * @return ResponseInterface
     */
    public function searchImgList(Sucai $request): ResponseInterface
    {
        $queryString = $request->input('query', '');
        $labels      = $request->input('labels', '');
        if (!empty($labels)) {
          $queryString .= $queryString . ' ' . $labels;
        }
        $order = $request->input('order', 'g_time');
        $lid= $request->input('lid', 0);
        //如果有筛选，则处理
        $queryParam = ['title' => ['or', "{$queryString}"], 'guanjianci' => ['or', "{$queryString}"]];
        if(!empty($lid)){
            $queryParam['id']=['in',[$lid]];
        }
//       $muLuId= $request->input('mulu_id', 0);
//        if(!empty($muLuId)){
//            $queryParam['mulu_id']=['in',[$muLuId]];
//        }
        $list = $this->sucaiService->searchImgList($queryString,$queryParam, $order);
        return $this->response->success($list);
    }

    /**
     * 收藏素材.
     * @param: id 收藏素材的id
     * @param: type 操作类型，1收藏，2取消，默认不传表示收藏
     * 返回:  {"code":0,"msg":"success","data":{"collect_num":1}}
     */
    public function collectImg(Sucai $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $type        = $request->input('type', 1);
        $remark      = $request->path();
        $collectNum  = $this->sucaiService->collectSucaiImg(intval($id), intval($type), (string)$remark);
        return $this->success(['collect_num'=>$collectNum]);
    }
}
