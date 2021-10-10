<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

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
     * 收藏素材.
     * @param: id 收藏素材的id
     * @param: type 操作类型，1收藏，2取消，默认不传表示收藏
     */
    public function collectSucaiImg(Sucai $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id   = $request->input('id');
        $type   = $request->input('type',1);
        $remark=$request->path();
        $collectNum  = $this->sucaiService->collectSucaiImg(intval($id),intval($type),(string)$remark);
        return $this->success($collectNum);
    }

}
