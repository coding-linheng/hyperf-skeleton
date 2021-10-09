<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ImgSizeStyle;
use App\Model\User;
use App\Repositories\V1\AlbumRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;

/**
 * AlbumService.
 *
 * @property User $userModel
 */
class AlbumService extends BaseService
{
    #[Inject]
    protected AlbumRepository $albumRepository;

    /**
     * getList.
     */
    public function getListPageRand(mixed $queryData): array
    {
        return $this->albumRepository->getListPageRand($queryData);
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     *
     * @param $order
     */
    public function searchAlbumList(mixed $queryData, $order): mixed
    {
        return $this->albumRepository->searchAlbumList($queryData, $order);
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     */
    public function getDetail(int $id): array
    {
        $detailArr = $this->albumRepository->getDetail(['l.id' => $id]);

        $detailArr['path']          = get_img_path($detailArr['path'], ImgSizeStyle::ALBUM_LIST_DETAIL_MID_PIC);
        $returnData['album_detail'] = $detailArr;

        //搜索该专辑中对应的图片
        $albumListArr = $this->albumRepository->getAlbumListDetailPage(['aid' => $detailArr['aid']], [], ['id', 'path', 'title']);

        if (!empty($albumListArr) && isset($albumListArr['list'])) {
            foreach ($albumListArr['list'] as $key => $val) {
                $albumListArr['list'][$key]['path'] = get_img_path($val['path'], ImgSizeStyle::ALBUM_LIST_DETAIL_SMALL_PIC);
            }
            $returnData['album_list'] = $albumListArr['list'];
        } else {
            $returnData['album_list'] = [];
        }

        return $returnData;
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     */
    public function getAlbumAuthor(int $id): Builder|null|Model
    {
        $detailArr = $this->albumRepository->getDetail(['l.id' => $id]);
        //判断是否是原创
        if (isset($detailArr['isoriginal']) && $detailArr['isoriginal'] != 2) {
            //不是自己的原创
            $detailArr = $this->albumRepository->getDetail(['l.id' => $detailArr['cid']]);
        }
        $detailArr['path'] = get_img_path($detailArr['path'], ImgSizeStyle::ALBUM_LIST_DETAIL_MID_PIC);
        return $detailArr;
    }

    /**
     * 获取灵感原图详情.
     *
     * @return array|bool
     */
    public function getOriginAlbumPic(int $id): array
    {
        $detailArr         = $this->albumRepository->getAlbumListDetail(['id' => $id], ['name', 'path', 'title']);
        if(empty($detailArr)){
            return [];
        }
        $detailArr         = is_array($detailArr) ?: $detailArr->toArray();
        $detailArr['path'] = get_img_path_private($detailArr['path']);
        return $detailArr;
    }
    /**
     * 采集图片灵感图片.
     * 请求参数 cid 采集灵感图片的id
     * 请求参数 aid 采集到属于我的专辑的id
     *
     * @return array|bool
     */
    public function captureAlbumImg(int $cid,$aid): array
    {
        $detailArr         = $this->albumRepository->getAlbumListDetail(['id' => $cid], ['name', 'path', 'title']);
        if(empty($detailArr)){
            return [];
        }
        $detailArr         = is_array($detailArr) ?: $detailArr->toArray();
        $detailArr['path'] = get_img_path_private($detailArr['path']);
        return $detailArr;
    }

}
