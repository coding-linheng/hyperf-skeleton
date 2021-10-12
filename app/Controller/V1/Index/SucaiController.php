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
     * {"code":0,"msg":"success","data":{"current_page":1,
     * "data":[
     *   {"id":77755,"path":"qzdj3z3qz.hn-bkt.clouddn.com20210116\/912531404e29-2b4c-4471-90c8-cfac37b8687e.zip\/xiaoyulantu","title":"地产520节日海报","shoucang":1,"downnum":3,"dtime":1621408783,"price":"5.00","leixing":2,"mulu":""},
     *   {"id":89045,"path":"qzdj3z3qz.hn-bkt.clouddn.com20210429\/15703cb092507-bea8-45dc-8d71-0b4dabf2c8e7.zip\/xiaoyulantu","title":"粉色520表白日立海报520甜蜜告白海报","shoucang":0,"downnum":0,"dtime":0,"price":"5.00","leixing":2,"mulu":""}
     * ],
     * "first_page_url":"http:\/\/192.168.10.9:9701\/v1\/material\/searchImgList?query=520%E5%91%8A%E7%99%BD%E5%AD%A3&page=1","from":1,"last_page":2712,
     * "last_page_url":"http:\/\/192.168.10.9:9701\/v1\/material\/searchImgList?query=520%E5%91%8A%E7%99%BD%E5%AD%A3&page=2712",
     * "next_page_url":"http:\/\/192.168.10.9:9701\/v1\/material\/searchImgList?query=520%E5%91%8A%E7%99%BD%E5%AD%A3&page=2",
     * "path":"http:\/\/192.168.10.9:9701\/v1\/material\/searchImgList","per_page":10,"prev_page_url":null,"to":5,"total":27115}}
     *
     * @return ResponseInterface
     */
    public function searchImgList(Sucai $request): ResponseInterface
    {
        $request->scene('searchImgList')->validateResolved();
        $queryString = $request->input('query', '');
        $labels      = $request->input('labels', '');
        if (!empty($labels)) {
          $queryString .= $queryString . ' ' . $labels;
        }

        $lid= $request->input('lid', 0);
        //如果有筛选，则处理
        $queryParam = ['title' => ['or', "{$queryString}"], 'guanjianci' => ['or', "{$queryString}"]];
        $where=[];
        if(!empty($lid)){
            $where=['leixing'=>$lid];
        }
//       $muLuId= $request->input('mulu_id', 0);
//        if(!empty($muLuId)){
//            $queryParam['mulu_id']=['in',[$muLuId]];
//        }
        //有搜索分类时不需要排序，按Es搜索引擎推荐排序
        $order = $request->input('order', '');
        if(empty($lid)&&empty($order)&&empty($queryString)){
            $order='g_time';
        }
        $list = $this->sucaiService->searchImgList($queryString,$queryParam,$where, $order);
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

    /**
     * 素材详情页.
     * @param: id 素材的id
     */
    public function getDetail(Sucai $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $list  = $this->sucaiService->getDetail(intval($id));
        return $this->success($list);

    }

    /**
     * 素材详情页--相关推荐.
     * @param: id 素材的id
     */
    public function recommendList(Sucai $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $type        = $request->input('type', 1);
        $remark      = $request->path();
        $collectNum  = $this->sucaiService->collectSucaiImg(intval($id), intval($type), (string)$remark);
        return $this->success(['collect_num'=>$collectNum]);
    }

    /**
     * 素材详情页--作者其他.
     * @param: id 素材的id
     */
    public function getListByAuthor(Sucai $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id          = $request->input('id');
        $type        = $request->input('type', 1);
        $remark      = $request->path();
        $collectNum  = $this->sucaiService->collectSucaiImg(intval($id), intval($type), (string)$remark);
        return $this->success(['collect_num'=>$collectNum]);
    }
}
