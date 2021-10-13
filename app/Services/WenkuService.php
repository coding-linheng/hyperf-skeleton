<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WenkuRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * WenkuService.
 *
 * @property User $userModel
 */
class WenkuService extends BaseService
{
    #[Inject]
    protected WenkuRepository $wenkuRepository;

    #[Inject]
    protected UserRepository $userRepository;

    /**
     * 模糊搜索文库数据，包含标题和关键字及其他筛选列表.
     * query 搜素关键字，热门搜索，不填为全部
     * order 排序字段： 最新 dtime，热门下载 downnum，推荐 tui
     * lid 不传该字段或者传0则表示默认全部，1共享，2原创, mulu_id：分类id.
     */
    public function getList(array $query): array|null
    {
        return $this->wenkuRepository->getSearchWenkuList($query);
    }

    /**
     * 详情页.
     * @param: id 文库的id
     *
     * @return null|array|mixed
     */
    public function getDetail(int $id): array|null
    {
        $uid  = user()['id'];
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $info = $info->toArray();

        //已删除
        if ($info['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '文库已删除！');
        }
        //未正常通过
        if ($info['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '文库暂时不能查看！');
        }
        $info['pdf'] = urlencode($info['pdf']);
        //获取详情图片
        $info['pictures'] = $this->wenkuRepository->getImgsUrl($info['img']);

        //是否关注
        $guanZhuUser =  $this->userRepository->isGuanzhuUser($uid, $info['uid']);

        if (empty($guanZhuUser)) {
            $info['guan_user'] = 1;
        } else {
            $info['guan_user'] = 2;
        }

        //是否收藏
        $shoucang = $this->wenkuRepository->isShouCang($uid, $info['id']);

        if (empty($shoucang)) {
            $info['shoucang'] = 1;
        } else {
            $info['shoucang'] = 2;
        }

        //增加浏览量
        $this->wenkuRepository->incLookNum($id);
        //广告位
        $info['advertisement'] = $this->wenkuRepository->getAdvertisement(7);
        //用户数据
        $info['userdata']  = $this->userRepository->getUserData($info['uid'], ['id', 'uid', 'name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1']);
        return [];
    }

    /**
     * 文库详情页--相关推荐.
     * @param: id 文库的id
     *
     * @return null|array|mixed
     */
    public function recommendList(int $id): array|null
    {
        //猜你喜欢
//        $this->caini();
//        $Wenkuajax=new Wenkuajax();
//        $wenkucaini=$Wenkuajax->getwenkutj(0,0,10,1);
//        foreach ($wenkucaini as $k=> $v){
//            $data = $v;
//            if($v['img']){
//                $pdfimg=getsucaijson($v['img']);
//                $wenkucaini[$k]['pdfimg']=$pdfimg;
//            }else{
//                $wenkucaini[$k]['pdfimg']='/'.$v['pdfimg'];
//            }
//        }

        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $info = $info->toArray();

        //如果有筛选，则处理
        //$queryString = $sucaiInfo['title'] . ' ' . $sucaiInfo['guanjianci'];
        //$queryParams  = ['title' => ['or', "{$queryString}"], 'guanjianci' => ['or', "{$queryString}"]];
        return $this->getList([]);
    }

    /**
     * 文库详情页--作者其他.
     * @param: id 文库的id
     */
    public function getListByAuthor(int $id): array|null
    {
        $info =  $this->wenkuRepository->getDetailInfoById($id);

        if (empty($info)) {
            throw new BusinessException(ErrorCode::ERROR, '文库不存在！');
        }
        $info = $info->toArray();
        $this->wenkuRepository->getListPageRand($info['uid']);
        return $info;
    }
}
