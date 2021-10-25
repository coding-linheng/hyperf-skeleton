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
     * 创建专辑.
     */
    public function addAlbum(Album $request): ResponseInterface
    {
        $request->scene('addAlbum')->validateResolved();
        $addData = $request->all();
        $data    = $this->albumService->addAlbum($addData);
        return $this->response->success(['id' => $data]);
    }

    /**
     * 获取专辑分类.
     */
    public function getAlbumCategory(): ResponseInterface
    {
        $data    = $this->albumService->getAlbumCategory();
        return $this->response->success($data);
    }

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
     * 请求参数 id 灵感图片的id
     * eg: /v1/album/getOriginAlbumPic?id=1569692
     * 返回该灵感图片原始图片，应该是加密后的原始图片.
     * {"code":0,"msg":"success","data":{"name":"ia_200001127.jpg","path":"http:\/\/qzdj3z3qz.hn-bkt.clouddn.com\/20210630\/3175jic1n2urpk8.jpg?e=1633766350&token=hl73g4h81_b6ysJCzQ_f_S49_0Ncu8C1mBJZHAje:mzL2-jquyhxZXjtomqUDxKV-tyk=","title":"二十四节气霜降地产海报"}}.
     */
    public function getOriginAlbumPic(Album $request): ResponseInterface
    {
        $request->scene('get')->validateResolved();
        $id   = $request->input('id');
        $list = $this->albumService->getOriginAlbumPic(intval($id));
        return $this->success($list);
    }

    /**
     * 获取原创作品列表.
     * order 排序字段： daytime最新，，looknum 热门浏览，guanzhu本周热门.
     */
    public function getOriginalWorkList(): ResponseInterface
    {
        $order       = $this->request->input('order', '');

        if (!empty($order) && !in_array($order, ['daytime', 'looknum', 'guanzhu'])) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '暂不支持的排序筛选');
        }
        $list = $this->albumService->getOriginalWorkList($order);
        return $this->response->success($list);
    }

    /**
     * 藏馆--获取品牌馆.
     * order 排序: daytime最新，looknum 热门浏览，guanzhu本周热门
     * page 页数.
     */
    public function getBrandCollectionList(): ResponseInterface
    {
        $queryData['brandscenes'] = $this->request->input('brandscenes', 0);
        $queryData['brandname']   = $this->request->input('brandname', 0);
        $queryData['branduse']    = $this->request->input('branduse', 0);
        $order                    = $this->request->input('order', '');

        if (!empty($order) && !in_array($order, ['daytime', 'looknum', 'guanzhu'])) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '暂不支持的排序筛选');
        }
        $list = $this->albumService->getBrandCollectionList($queryData, $order);
        return $this->response->success($list);
    }

    /**
     * 藏馆--获取地产馆.
     * paintcountry ：绘画国家分类id
     * paintname :绘画名字分类id
     * paintstyle :绘画风格分类id
     * order 排序
     * page 页数.
     */
    public function getLandedCollectionList(): ResponseInterface
    {
        $queryData['paintcountry']  = $this->request->input('paintcountry', 0);
        $queryData['paintname']     = $this->request->input('paintname', 0);
        $queryData['paintstyle']    = $this->request->input('paintstyle', 0);
        $order                      = $this->request->input('order', '');

        if (!empty($order) && !in_array($order, ['daytime', 'looknum', 'guanzhu'])) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '暂不支持的排序筛选');
        }
        $list = $this->albumService->getLandedCollectionList($queryData, $order);
        return $this->response->success($list);
    }

    /**
     * 根据专辑id获取对应专辑里面的所有图片信息
     * id ：专辑id
     * page 页数.
     * {"code":0,"msg":"success","data":{"current_page":1,"data":[
     * {"id":1765,"path":"qzdj3z3qz.hn-bkt.clouddn.com20190730\/3v0g8ljh7f2.png\/xiaoyulantu","title":"高端中式奢华题案房地产豪宅别墅地产广告宣传设计","looknum":38,"downnum":0,"dtime":0},
     * {"id":1778,"path":"qzdj3z3qz.hn-bkt.clouddn.com20190730\/3hh9v758x9k.jpg\/xiaoyulantu","title":"高端中式奢华题案房地产豪宅别墅地产广告宣传设计","looknum":43,"downnum":0,"dtime":0},
     * {"id":1792,"path":"qzdj3z3qz.hn-bkt.clouddn.com20190730\/30332a06nsdp9.jpg\/xiaoyulantu","title":"高端中式奢华题案房地产豪宅别墅地产广告宣传设计","looknum":50,"downnum":0,"dtime":0}
     * ],"first_page_url":"http:\/\/192.168.10.8:9701\/v1\/album\/getAlbumListById?page=1","from":1,
     * "last_page":16,"last_page_url":"http:\/\/192.168.10.8:9701\/v1\/album\/getAlbumListById?page=16",
     * "next_page_url":"http:\/\/192.168.10.8:9701\/v1\/album\/getAlbumListById?page=2",
     * "path":"http:\/\/192.168.10.8:9701\/v1\/album\/getAlbumListById",
     * "per_page":20,"prev_page_url":null,"to":20,"total":319
     * }}.
     */
    public function getAlbumListById(): ResponseInterface
    {
        $id  = $this->request->input('id', 0);

        if (empty($id)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '专辑id 不能为空！');
        }
        $list = $this->albumService->getAlbumListById((int)$id);
        return $this->response->success($list);
    }
}
