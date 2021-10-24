<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\User;
use App\Model\Userlookling;
use App\Repositories\V1\AlbumRepository;
use App\Repositories\V1\SucaiRepository;
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

    #[Inject]
    protected SucaiRepository $sucaiRepository;

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

        if (!empty($detailArr)) {
            $detailArr = $detailArr->toArray();
        } else {
            throw new BusinessException(ErrorCode::ERROR, '内容不存在！');
        }
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
     *
     * @return array|void
     */
    public function getAlbumAuthor(int $id): array|null
    {
        $detailArr = $this->albumRepository->getDetail(['l.id' => $id]);

        if (!empty($detailArr)) {
            $detailArr = $detailArr->toArray();
        } else {
            throw new BusinessException(ErrorCode::ERROR, '内容不存在！');
        }
        //判断是否是原创
        if (isset($detailArr['isoriginal']) && $detailArr['isoriginal'] != 2) {
            //不是自己的原创
            $detailArr = $this->albumRepository->getDetail(['l.id' => $detailArr['cid']]);

            if (!empty($detailArr)) {
                $detailArr = $detailArr->toArray();
            } else {
                throw new BusinessException(ErrorCode::ERROR, '内容不存在！');
            }
        }
        $detailArr['path'] = get_img_path($detailArr['path'], ImgSizeStyle::ALBUM_LIST_DETAIL_MID_PIC);
        return $detailArr;
    }

    /**
     * 获取灵感原图详情.
     *
     * @return array|void
     */
    public function getOriginAlbumPic(int $id): array
    {
        $albumListArr = $this->albumRepository->getAlbumListDetail(['id' => $id], ['id', 'yid', 'aid', 'name', 'path', 'title']);

        if (empty($albumListArr)) {
            throw new BusinessException(ErrorCode::ERROR, '图片不存在！');
        }
        $albumListArr = is_array($albumListArr) ?: $albumListArr->toArray();
        // 查询该专辑是否为自己上传的，
        $albumInfo = $this->albumRepository->getAlbumDetailInfo(
            ['id' => $albumListArr['aid']],
            ['id', 'yid', 'uid', 'ltui', 'tui', 'isoriginal', 'name']
        );

        if (empty($albumInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '图片专辑不存在！');
        }
        $albumInfo = is_array($albumInfo) ?: $albumInfo->toArray();

        //如果没登录则不能看
        if (!isset(user()['id'])) {
            throw new BusinessException(ErrorCode::AUTH_ERROR, '登录后查看！');
        }
        //检查，当天查询原图地址的次数,如果是自己的则不限,别人制超过每天最大限制则拒绝
        if ($albumInfo['uid'] != user()['id'] || $albumInfo['yid'] != 0 || $albumListArr['yid'] != 0) {
            if (!$this->isCanLookOriginImg($id, user()['id'])) {
                throw new BusinessException(ErrorCode::DAY_MAX_LOOK_TIMES, '用户每天暂时只可查看100张灵感原图！');
            }
        }
        $detailArr['path'] = get_img_path_private($albumListArr['path']);
        return $detailArr;
    }

    /**
     * 是否可以查看原图
     * 请求参数 id 采集灵感图片的id.
     *
     * @param mixed $id
     * @param mixed $uid
     * @return bool
     */
    public function isCanLookOriginImg($id, $uid)
    {
//        $tui=db('userdata')->where(['uid'=>$this->uid])->value('tui');//查看推荐人数
//        $isview=db('user')->where(['id'=>$this->uid])->value('isview');//是否有查看权限
        $max  = 50;
        $time = strtotime(date('Y-m-d'));
        $info = Userlookling::where(['uid' => $uid, 'time' => $time])->first();

        if (empty($info)) {
            $add         = [];
            $add['uid']  = user()['id'];
            $add['time'] = $time;
            $add['num']  = 1;
            $lidarr      = [];
            $lidarr[]    = $id;
            $add['lip']  = serialize($lidarr); //序列化灵感ID
            $lastId      = Userlookling::insertGetId($add);

            if (!$lastId) {
                return false;
            }
            return true;
        }
        $info = $info->toArray();
        //如果有
        if ($info['num'] > $max) {
            return false;
        }
        $lidarr = unserialize($info['lip']);
        //现在查看的是之前查看过的不统计
        if (!in_array($id, $lidarr)) {
            $lidarr[]    = $id;
            $save        = [];
            $save['num'] = $info['num'] + 1;
            $save['lip'] = serialize($lidarr);

            if (!Userlookling::where(['id' => $info['id']])->update($save)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 收藏灵感图片.
     * 请求参数 id 收藏灵感图片的id.
     *
     * @param $type
     *
     * @param $remark
     *
     * @return null|int|mixed
     */
    public function collectAlbumImg(int $id, $type, $remark): int|null
    {
        //判断图片是否存在
        $albumlistInfo = $this->albumRepository->getAlbumListDetail(
            ['id' => $id],
            ['id', 'aid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']
        );

        if (empty($albumlistInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '图片不存在！');
        }

        $albumInfo = $this->albumRepository->getAlbumDetailInfo(
            ['id' => $albumlistInfo['aid']],
            ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name']
        );

        if ($albumInfo['uid'] == user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的作品！');
        }

        //取消采集
        if ($type == 2) {
            return $this->albumRepository->deleteCollectAlbumImg($albumlistInfo, $albumInfo, user()['id']);
        }
        //采集
        return $this->albumRepository->collectAlbumImg($albumlistInfo, $albumInfo, user()['id'], $remark);
    }

    /**
     * 收藏灵感专辑.
     * 请求参数 id 收藏专辑的id.
     *
     * @param $type
     *
     * @param $remark
     *
     * @return null|int|mixed
     */
    public function collectAlbum(int $id, $type): int|null
    {
        //判断专辑是否存在
        $albumInfo = $this->albumRepository->getAlbumDetailInfo(
            ['id' => $id],
            ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name', 'status', 'week', 'weekguanzhu', 'guanzhu', 'daynum', 'daytime']
        );

        if ($albumInfo['uid'] == user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的作品！');
        }

        //取消采集
        if ($type == 2) {
            return $this->albumRepository->deleteCollectAlbum($albumInfo, user()['id']);
        }
        //采集
        return $this->albumRepository->collectAlbum($albumInfo, user()['id']);
    }


  /**
   * 获取收藏该图片的设计师列表.
   * 请求参数 id 收藏图片的id.
   *
   * @param int $id
   *
   * @return array
   */
  public function getDesignerByCollectImg(int $id): array {
    //判断图片是否存在
    $albumlistInfo = $this->albumRepository->getAlbumListDetail(
      ['id' => $id],
      ['id', 'aid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']
    );

    if (empty($albumlistInfo)) {
      throw new BusinessException(ErrorCode::ERROR, '图片不存在！');
    }
    $designerList= $this->albumRepository->getDesignerByCollectImg($id);
    if (!empty($designerList) && isset($designerList['data']) && !empty($designerList['data'])) {
      foreach ($designerList['data'] as $key => &$val) {
        if (!isset($val['id'])) {
          unset($designerList['data'][$key]);
          continue;
        }
        $tmp                = $val;
        $tmp['sucai_list']  = [];

        if ($val['sucainum'] > 1) {
          //循环从素材中获取图片，只取6个
          $where              = ['uid' => $val['id']];
          $imgLists           = $this->sucaiRepository->searchImgList('', [], $where, '', 6);
          $tmp['sucai_list']  = $imgLists['data'] ?? [];
        }
        $designerList['data'][$key]  = $tmp;
      }
    }
   return $designerList;
  }


  /**
   * 获取收藏该专辑的设计师列表.
   * 请求参数 id 收藏专辑的id.
   *
   * @param int $id
   *
   * @return array
   */
  public function getDesignerByCollectAlbum(int $id): array {
    //判断专辑是否存在
    $albumInfo = $this->albumRepository->getAlbumDetailInfo(
      ['id' => $id],
      ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name', 'status', 'week', 'weekguanzhu', 'guanzhu', 'daynum', 'daytime']
    );

    if (empty($albumInfo)) {
      throw new BusinessException(ErrorCode::ERROR, '该专辑不存在！');
    }

    $designerList= $this->albumRepository->getDesignerByCollectAlbum($id);
    if (!empty($designerList) && isset($designerList['data']) && !empty($designerList['data'])) {
      foreach ($designerList['data'] as $key => &$val) {
        if (!isset($val['id'])) {
          unset($designerList['data'][$key]);
          continue;
        }
        $tmp                = $val;
        $tmp['sucai_list']  = [];

        if ($val['sucainum'] > 1) {
          //循环从素材中获取图片，只取6个
          $where              = ['uid' => $val['id']];
          $imgLists           = $this->sucaiRepository->searchImgList('', [], $where, '', 6);
          $tmp['sucai_list']  = $imgLists['data'] ?? [];
        }
        $designerList['data'][$key]  = $tmp;
      }
    }
    return $designerList;
  }

    /**
     * 采集图片灵感图片.
     * 请求参数 cid 采集灵感图片的id
     * 请求参数 aid 采集到属于我的专辑的id.
     *
     * @param mixed $aid
     * @return array|bool
     */
    public function captureAlbumImg(int $cid, $aid, string $title): array
    {
        $albumInfo     = $this->albumRepository->getAlbumDetailInfo(
            ['id' => $aid],
            ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name', 'num']
        );
        $albumlistInfo = $this->albumRepository->getAlbumListDetail(
            ['id' => $cid],
            ['id', 'aid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'caiji']
        );

        if (empty($albumInfo) || empty($albumlistInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '图片或者专辑不存在！');
        }

        $albumInfo     = $albumInfo->toArray();
        $albumlistInfo = $albumlistInfo->toArray();
        //检测是否满足可以采集条件
        $this->captureAlbumImgCheck($albumInfo, $albumlistInfo);

        //开始采集
        $albumlistInfoNew          = $albumlistInfo;
        $albumlistInfoNew['title'] = empty($title) ? $albumlistInfo['title'] : $title;
        $albumlistInfoNew['aid']   = $aid;
        $albumlistInfoNew['cid']   = $cid;
        $albumlistInfoNew['time']  = time();
        $albumlistInfoNew['yid']   = $albumlistInfo['id'];
        unset($albumlistInfoNew['id']);

        return $this->albumRepository->captureAlbumImg($albumlistInfoNew, $albumInfo, $albumlistInfo);
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     *
     * @param $order
     */
    public function getOriginalWorkList($order): mixed
    {
        //此处可能会搜索专辑展示 isoriginal 是否原创 1否2是
        $where = 'del=1 and status=2 and isoriginal=2 ';

        if (!empty($order)) {
            $order = $order . ' desc ,id desc';
        }
        return $this->albumRepository->getAlbum($where, $order);
    }

    /**
     * 藏馆--获取品牌馆.
     *
     * @param $order
     */
    public function getBrandCollectionList(mixed $queryData, $order): mixed
    {
        $where = 'del=1 and status=2 and (brandscenes>0 OR brandname>0 OR branduse>0) ';
        //此处可能会搜索专辑展示 isoriginal 是否原创 1否2是
        if (isset($queryData['brandscenes']) && !empty($queryData['brandscenes'])) {
            $where .= " AND brandscenes={$queryData['brandscenes']}";
        }

        if (isset($queryData['brandname']) && !empty($queryData['brandname'])) {
            $where .= " AND brandname={$queryData['brandname']}";
        }

        if (isset($queryData['branduse']) && !empty($queryData['branduse'])) {
            $where .= " AND branduse={$queryData['branduse']}";
        }

        if (!empty($order)) {
            $order = $order . ' desc ,id desc';
        }
        return $this->albumRepository->getAlbum($where, $order);
    }

    /**
     *  藏馆--获取地产馆.
     * @param $order
     */
    public function getLandedCollectionList(mixed $queryData, $order): mixed
    {
        $where = 'del=1 and status=2 and (paintcountry>0 OR paintname>0 OR paintstyle>0)';
        //此处可能会搜索专辑展示 isoriginal 是否原创 1否2是
        if (isset($queryData['paintstyle']) && !empty($queryData['paintstyle'])) {
            $where .= " AND paintstyle={$queryData['paintstyle']}";
        }

        if (isset($queryData['paintname']) && !empty($queryData['paintname'])) {
            $where .= " AND paintname={$queryData['paintname']}";
        }

        if (isset($queryData['paintcountry']) && !empty($queryData['paintcountry'])) {
            $where .= " AND paintcountry={$queryData['paintcountry']}";
        }

        if (!empty($order)) {
            $order = $order . ' desc ,id desc';
        }
        return $this->albumRepository->getAlbum($where, $order);
    }

    //查看专辑详情
    public function getAlbumListById($id): array
    {
        return $this->albumRepository->getAlbumListById($id);
    }

    //采集
    private function captureAlbumImgCheck($albumInfo, $albumlistInfo)
    {
        $uid = user()['id'] ?? 0;

        if ($uid == 0) {
            throw new BusinessException(ErrorCode::ERROR, '请重新登录后重试！');
        }

        if (empty($albumInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '专辑不存在,无法采集');
        }

        if (empty($albumlistInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '图片不存在,无法采集');
        }

        if ($albumInfo['ltui'] == 2 || $albumInfo['tui'] == 2) {
            throw new BusinessException(ErrorCode::ERROR, '推荐专辑不能操作！');
        }

        if ($albumInfo['isoriginal'] == 2) {
            throw new BusinessException(ErrorCode::ERROR, '不能采集到原创专辑！');
        }

        if ($albumInfo['uid'] != user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请刷新页面重新登录后操作！');
        }

        //图片本来就在这张专辑里面
        if ($albumlistInfo['aid'] == $albumInfo['id']) {
            throw new BusinessException(ErrorCode::ERROR, '该图片已经出现在你的 ' . $albumInfo['name'] . '专辑里');
        }

        return true;
    }
}
