<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ImgSizeStyle;
use App\Model\User;
use App\Repositories\V1\AlbumRepository;
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
     * @param mixed $queryData
     * @param $order
     *
     * @return mixed
     */
    public function searchAlbumList($queryData, $order)
    {
        return $this->albumRepository->searchAlbumList($queryData, $order);
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     */
    public function getDetail(int $id): mixed
    {
        $detailArr = $this->albumRepository->getDetail(['l.id' => $id]);

        if (empty($detailArr)) {
            return [];
        }

        $detailArr['path']          = env('PUBLIC_DOMAIN') . '/' . $detailArr['path'] . '/' . ImgSizeStyle::ALBUM_LIST_DETAIL_MID_PIC;
        $returnData['album_detail'] = $detailArr;

        //搜索该专辑中对应的图片
        $albumListArr = $this->albumRepository->getAlbumListDetailPage(['aid' => $detailArr['aid']], [], ['id', 'path', 'title']);

        if (!empty($albumListArr) && isset($albumListArr['list'])) {
            foreach ($albumListArr['list'] as $key => $val) {
                $albumListArr['list'][$key]['path'] = env('PUBLIC_DOMAIN') . '/' . $val['path'] . '/' . ImgSizeStyle::ALBUM_LIST_DETAIL_SMALL_PIC;
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
  public function getAlbumAuthor(int $id): mixed
  {
    $detailArr = $this->albumRepository->getDetail(['l.id' => $id]);

    if(empty($detailArr)) {
      return [];
    }
    //判断是否是原创
    if(isset($detailArr['isoriginal']) && $detailArr['isoriginal']!=2){
      //不是自己的原创
      $detailArr = $this->albumRepository->getDetail(['l.id' =>  $detailArr['cid']]);
    }
    $detailArr['path']          = env('PUBLIC_DOMAIN') . '/' . $detailArr['path'] . '/' . ImgSizeStyle::ALBUM_LIST_DETAIL_MID_PIC;
    return $detailArr;
  }

}
