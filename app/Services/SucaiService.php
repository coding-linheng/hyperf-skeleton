<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\User;
use App\Repositories\V1\SucaiRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WenkuRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * SucaiService.
 *
 * @property User $userModel
 */
class SucaiService extends BaseService
{
    #[Inject]
    protected SucaiRepository $sucaiRepository;

    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected WenkuRepository $wenkuRepository;

    /**
     * 模糊搜索素材数据，包含标题和关键字及其他筛选.
     *
     * @param $query
     *
     * @param $whereParam
     * @param $where
     * @param $order
     *
     * @return mixed
     */
    public function searchImgList($query, $whereParam, $where, $order)
    {
        return $this->sucaiRepository->searchImgList($query, $whereParam, $where, $order);
    }

    /**
     * 收藏素材图片.
     * 请求参数 id 收藏素材图片的id.
     *
     * @param $type
     *
     * @param $remark
     *
     * @return null|int|mixed
     */
    public function collectSucaiImg(int $id, $type, $remark): int|null
    {
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgInfo(['id' => $id], ['id', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }

        if ($sucaiInfo['uid'] == user()['id']) {
            throw new BusinessException(ErrorCode::ERROR, '请勿操作自己的作品！');
        }
        //取消采集
        if ($type == 2) {
            return $this->sucaiRepository->deleteCollectSucaiImg($sucaiInfo, user()['id']);
        }
        //采集
        return $this->sucaiRepository->collectSucaiImg($sucaiInfo, user()['id'], $remark);
    }


    /**
     * 素材详情页.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function getDetail(int $id): array|null
    {
        $uid = user()['id'];
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.del', 'img.status', 'img.img', 'uid', 'suffix', 'size', 'height', 'name',  'title', 'guanjianci',  'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        $sucaiInfo = $sucaiInfo->toArray();
        //已删除
        if ($sucaiInfo['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '素材已删除！');
        }
        //未正常通过
        if ($sucaiInfo['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '素材暂时不能查看！');
        }
        //获取详情图片
        $info['pictures'] = $this->sucaiRepository->getImgsUrl($sucaiInfo['img']);

        //查询他的作品个数
        $sucaiInfo['total_num'] = $this->sucaiRepository->totalImgCount(['uid' => $sucaiInfo['uid'], 'del' => 0, 'status' => 3]);

        //是否关注
        $guanZhuUser =  $this->userRepository->isGuanzhuUser($uid, $sucaiInfo['uid']);

        if (empty($guanZhuUser)) {
            $sucaiInfo['guan_user'] = 1;
        } else {
            $sucaiInfo['guan_user'] = 2;
        }

        //是否收藏
        $shoucang = $this->sucaiRepository->isShouCangImg($uid, $sucaiInfo['id']);

        if (empty($shoucang)) {
            $sucaiInfo['shoucang'] = 1;
        } else {
            $sucaiInfo['shoucang'] = 2;
        }
        //类型
        $sucaiInfo['fenlei'] = $this->sucaiRepository->getFenLei($id);
        $sucaiInfo['format'] = $this->sucaiRepository->getImgFormat($id);
        //浏览量
        $this->sucaiRepository->IncImgCount($id);
        //广告位
        $sucaiInfo['advertisement'] = $this->sucaiRepository->getSuCaiAdvertisement();
        //关键词
        $linglebel = array_unique(explode(' ', $sucaiInfo['guanjianci']));

        foreach ($linglebel as $k => $v) {
            if (trim($v) == '') {
                unset($linglebel[$k]);
            }
        }
        $sucaiInfo['key_words'] = $linglebel;
        $sucaiInfo['userdata']  = $this->userRepository->getUserData($sucaiInfo['uid'], ['id', 'uid', 'name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1']);
        return $sucaiInfo;
    }

    /**
     * 相关推荐.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function recommendList(int $id): array|null
    {
        //本类素材
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.del', 'img.status', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        //如果有筛选，则处理
        $queryString = $sucaiInfo['title'] . ' ' . $sucaiInfo['guanjianci'];
        $queryParam  = ['title' => ['or', "{$queryString}"], 'guanjianci' => ['or', "{$queryString}"]];
        $where       = [];
        return $this->searchImgList($queryString, $queryParam, $where, '');
    }

    /**
     * 素材详情页--作者其他.
     */
    public function getListByAuthor(int $id): array|null
    {
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id', 'img.uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci', 'shoucang']);

        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        return $this->sucaiRepository->getListPageRand($sucaiInfo['uid']);
    }

    /**
     * 获取素材的下载地址.
     * @param: id 素材的id
     *
     * @return null|array|mixed
     */
    public function getDownUrl(int $id): array|null
    {
        $uid = user()['id'];
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgInfo(['id' => $id], ['id', 'del', 'status', 'img', 'path','path', 'uid', 'suffix', 'size', 'height', 'name',  'title', 'guanjianci',  'shoucang']);
        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        $sucaiInfo = $sucaiInfo->toArray();
        //已删除
        if ($sucaiInfo['del'] == 1) {
            throw new BusinessException(ErrorCode::ERROR, '素材已删除！');
        }
        //未正常通过
        if ($sucaiInfo['status'] != 3) {
            throw new BusinessException(ErrorCode::ERROR, '素材暂时不能下载！');
        }

        $downLoadUrl=get_img_path_private($sucaiInfo['path']);
        //如果是自己下载自己的则直接返回
        if ($sucaiInfo['uid'] == $uid && $uid!=0) {
            return ['suffix'=>$sucaiInfo['suffix'],'title'=>$sucaiInfo['title'],'downLoadUrl'=>$downLoadUrl];
        }

        //如果是下载别人的则需要处理下载数量，扣除共享分等
        if($sucaiInfo['leixing']==1 && $sucaiInfo['price']!=0){
            //下载共享素材
            $ret=$this->downShareSucai($sucaiInfo);
        }elseif($sucaiInfo['leixing']==2){
            //下载原创素材
            $ret=$this->downDcSucai($sucaiInfo);
        }else{
            //免费下载
            $ret=$this->downFreeSucai($sucaiInfo);
        }
        if(!$ret){
            throw new BusinessException(ErrorCode::ERROR, '该素材暂时无法下载！');
        }
        return ['suffix'=>$sucaiInfo['suffix'],'title'=>$sucaiInfo['title'],'downLoadUrl'=>$downLoadUrl];
    }

    /**
     * 下载共享素材
     *
     * @param $sucaiInfo
     *
     * @return bool
     */
    private function downShareSucai($sucaiInfo){
        $time=strtotime(date("Y-m-d"));
        $sucaidown= $this->sucaiRepository->getSuCaiDown(['uid'=>$this->uid,'time'=>$time]);
        if(!empty($sucaidown)){
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr=explode(',', $sucaidown['ids']);
            if(in_array($sucaiInfo['id'],$arr)){
                return true;
            }
        }
        return true;
    }

    /**
     * 下载原创素材
     *
     * @param $sucaiInfo
     *
     * @return bool
     */
    private function downDcSucai($sucaiInfo){
        $time=strtotime(date("Y-m-d"));
        $sucaidown= $this->sucaiRepository->getSuCaiDown(['uid'=>$this->uid,'time'=>$time]);
        if(!empty($sucaidown)){
            //当天下载过的可以直接下载，不扣除分数也不增加下载次数
            $arr=explode(',', $sucaidown['ids']);
            if(in_array($sucaiInfo['id'],$arr)){
                return true;
            }
        }
        return true;
    }

    /**
     * 下载免费素材
     * @return bool
     */
    private function downFreeSucai($sucaiInfo){
        //判断是否有权限
        //$quanxian=$this->jurisdiction($this->uid);

        return true;
//        $quanxian=$this->jurisdiction($this->uid);
//
//        $time=strtotime(date('Y-m-d'));
//        if($quanxian===false || $quanxian['sucai']==0){
//            $dayinfo=db('daydownsucai')->where(['uid'=>$this->uid,'time'=>$time])->find();
//            if(empty($dayinfo)){
//                $add=[];
//                $add['uid']=$this->uid;
//                $add['time']=$time;
//                $add['num']=1;
//                $ids=db('daydownsucai')->insert($add);
//                if(!$ids){
//                    db()->rollback();
//                    return json([RESULT_ERROR,'操作失败']);
//                }
//            }else{
//                db()->rollback();
//                return json(['nop',[0,0]]);//原创文档，需要通过充值原创币才可点击下载
//            }
//            $ids=$this->addwaterdownsucai($id,$bid,$this->uid,0);
//            if($ids!==true){
//                db()->rollback();
//                return json($ids);
//            }
//        }
//        elseif($quanxian['sucai']!=0){
//            //1代表vip1  5个；2代表vip2 8个；3代表vip3 10个；4代表vip4 20个
//            $dayinfo=db('daydownsucai')->where(['uid'=>$this->uid,'time'=>$time])->find();
//            if(empty($dayinfo)){
//                $add=[];
//                $add['uid']=$this->uid;
//                $add['time']=$time;
//                $add['num']=1;
//                $ids=db('daydownsucai')->insert($add);
//            }else{
//                if(($quanxian['sucai']==1 && $dayinfo['num']>=5) || ($quanxian['sucai']==2 && $dayinfo['num']>=8) || ($quanxian['sucai']==3 && $dayinfo['num']>=10) || ($quanxian['sucai']==4 && $dayinfo['num']>=20)){
//                    db()->rollback();
//                    return json(['nop',$quanxian['sucai']]);
//                }
//                $ids=db('daydownsucai')->where(['uid'=>$this->uid,'time'=>$time])->setInc('num');
//            }
//            if(!$ids){
//                db()->rollback();
//                return json([RESULT_ERROR,'操作失败']);
//            }
//            //增加下载流水
//            $ids=$this->addwaterdownsucai($id,$bid,$this->uid,0);
//            if($ids!==true){
//                db()->rollback();
//                return json($ids);
//            }
//        }
//
//        $ids=db('img')->where(['id'=>$info['id']])->setInc('downnum');
//        if(!$ids){
//            db()->rollback();
//            return json([RESULT_ERROR,'操作失败']);
//        }
//        //7天下载
//        $ids=$this->weekcaozuo($info['id'],'img');
//        if(!$ids){
//            db()->rollback();
//            return json([RESULT_ERROR,'操作失败']);
//        }
//        db()->commit();
//        cache($this->uid.$id.'sucai',true,604800);
//        $path=getimgurls($info['path']);
    }

}
