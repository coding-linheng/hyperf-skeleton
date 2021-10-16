<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Advertisement;
use App\Model\Bannerindex;
use App\Model\Blogroll;
use App\Model\RequestLog as RequestLogModel;
use App\Model\Top;
use App\Repositories\BaseRepository;

/**
 * RequestLogRepository.
 */
class CommonRepository extends BaseRepository
{

    public function getBannerIndex(): array
    {
        $bannerIndex=Bannerindex::query()->orderByDesc("id")->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if(!empty($bannerIndex)) foreach($bannerIndex as $key=>&$val){
            $url= $this->getPictureUrlById($val['img']);
            if(!empty($url)){
                $bannerIndex[$key]['img']=get_img_path_private($url);
            }
        }
        return $bannerIndex;
    }


    public function getAdvertisement(){
        $advertisement=Advertisement::query()->orderByDesc("id")->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if(!empty($advertisement)) foreach($advertisement as $key=>&$val){
            $url= $this->getPictureUrlById($val['img']);
            if(!empty($url)){
                $advertisement[$key]['img']=get_img_path_private($url);
            }
        }
        return $advertisement;
    }

    public function getIndexTopAdvertisement(){
        $topAdvertisement=Top::query()->orderByDesc("id")->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if(!empty($topAdvertisement))  for($i=2;$i<=10;$i++){
            $url= $this->getPictureUrlById($topAdvertisement['img'.$i]);
            if(!empty($url)){
                $topAdvertisement['img'.$i]=get_img_path_private($url);
            }
        }
        return $topAdvertisement;
    }



    //友情链接
    public function getBlogRoll(){
        //友情链接
        $blogRoll=Blogroll::query()->where(['status'=>1])->orderBy("sort")->get()->toArray();
        foreach($blogRoll as $k=>$v){
            if($v['img_id']!=0){
                //将id 转成图片缓存
                $url= $this->getPictureUrlById($v['img_id']);
                if(!empty($url)){
                    $blogRoll[$k]['img']=get_img_path_private($url);
                }else{
                    return '/static/module/admin/img/onimg.png';
                }
            }
        }
        return $blogRoll;
    }

}
