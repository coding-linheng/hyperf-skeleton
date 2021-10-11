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
     * query 查询关键字选填，不填为全部
     * order 排序字段：最新采集 dtime，最新更新 g_time，上周最高采集 caiji
     * labels 标签筛选 可选.
     */
    public function searchImgList(Sucai $request): ResponseInterface
    {
//        $queryString = $this->request->input('query', '');
//        $labels      = $this->request->input('labels', '');
//
//        if (!empty($labels)) {
//          $queryString .= $queryString . ' ' . $labels;
//        }
//        $order = $this->request->input('order', '');
//
//        if (!empty($order) && !in_array($order, ['dtime', 'g_time', 'caiji'])) {
//          $this->response->error(ErrorCode::VALIDATE_FAIL, '暂不支持的排序筛选');
//        }
//        $list = $this->albumService->searchAlbumList($queryString, $order);
        return $this->response->success([]);
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
