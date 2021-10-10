<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\Shouling;
use App\Model\User;
use App\Model\Userlookling;
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
     *
     * @param  int  $id
     *
     * @return array|void
     */
    public function getAlbumAuthor(int $id): array|null
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
     * @return array|void
     */
    public function getOriginAlbumPic(int $id): array
    {
        $albumListArr         = $this->albumRepository->getAlbumListDetail(['id' => $id], ['name', 'path', 'title']);

        if (empty($albumListArr)) {
            throw new BusinessException(ErrorCode::ERROR, '图片不存在！');
        }
        $albumListArr         = is_array($albumListArr) ?: $albumListArr->toArray();
        // 查询该专辑是否为自己上传的，
        $albumInfo  = $this->albumRepository->getAlbumDetailInfo(['id' => $albumListArr['aid']], ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name']);
        if (empty($albumInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '图片专辑不存在！');
        }
        //如果没登录则不能看
        if(!isset(user()['id'])){
            throw new BusinessException(ErrorCode::AUTH_ERROR, '登录后查看！');
        }
        //检查，当天查询原图地址的次数,如果是自己的则不限,别人制超过每天最大限制则拒绝
        if ($albumInfo['uid'] != user()['id'] || $albumInfo['yid']!=0 || $albumListArr['yid']!=0) {
            if (!$this->isCanLookOriginImg($id)) {
                throw new BusinessException(ErrorCode::DAY_MAX_LOOK_TIMES, '您已经达到当日查询的最高次数！');
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
     * @return bool
     */
    public function isCanLookOriginImg($id)
    {

//        $tui=db('userdata')->where(['uid'=>$this->uid])->value('tui');//查看推荐人数
//        $isview=db('user')->where(['id'=>$this->uid])->value('isview');//是否有查看权限
        $max=50;
        $time=strtotime(date('Y-m-d'));
        $info=Userlookling::where(['uid'=>$this->uid,'time'=>$time])->first()->toArray();

        if(empty($info)){
            $add=[];
            $add['uid']=user()['id'];
            $add['time']=$time;
            $add['num']=1;
            $lidarr=[];
            $lidarr[]=$id;
            $add['lip']=serialize($lidarr);//序列化灵感ID
            $lastId= Userlookling::insertGetId($add);
            if(!$lastId){
                return false;
            }else{
                return true;
            }
        }else{
            //如果有
            if($info['num']>$max){
                return false;
            }
            $lidarr=unserialize($info['lip']);
            //现在查看的是之前查看过的不统计
            if(!in_array($id,$lidarr)){
                $lidarr[]=$id;
                $save=[];
                $save['num']=$info['num']+1;
                $save['lip']=serialize($lidarr);
                if(!Userlookling::where(['id'=>$info['id']])->update($save)){
                    return false;
                }
            }
            return true;
        }
    }


    /**
     * 收藏灵感图片.
     * 请求参数 id 收藏灵感图片的id
     *
     * @param  int  $id
     *
     * @param       $type
     *
     * @param       $remark
     *
     * @return int|null|mixed
     */
    public function collectAlbumImg(int $id,$type,$remark): int|null
    {
        //判断图片是否存在
        $albumlistInfo = $this->albumRepository->getAlbumListDetail(['id' => $id], ['id', 'aid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']);
        if(empty($albumlistInfo)){
            throw new BusinessException(ErrorCode::ERROR, '图片不存在！');
        }

        $albumInfo     = $this->albumRepository->getAlbumDetailInfo(['id' => $albumlistInfo['aid']], ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name']);
        if($albumInfo['uid']==user()['id']){
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的作品！');
        }

        //取消采集
        if($type==2){
           return  $this->albumRepository->deleteCollectAlbumImg($albumlistInfo, $albumInfo, user()['id']);
        }else{
            //采集
            return $this->albumRepository->collectAlbumImg($albumlistInfo, $albumInfo, user()['id'],$remark);
        }
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
        $albumInfo     = $this->albumRepository->getAlbumDetailInfo(['id' => $aid], ['id', 'uid', 'ltui', 'tui', 'isoriginal', 'name']);
        $albumlistInfo = $this->albumRepository->getAlbumListDetail(['id' => $cid], ['id', 'aid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'caiji']);

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
