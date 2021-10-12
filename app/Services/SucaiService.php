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
    public function searchImgList($query,$whereParam,$where,$order)
    {
        return $this->sucaiRepository->searchImgList($query,$whereParam,$where,$order);
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
        //判断图片是否存在
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['id' => $id], ['id', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'shoucang']);
        if (empty($sucaiInfo)) {
            throw new BusinessException(ErrorCode::ERROR, '素材不存在！');
        }
        $sucaiInfo=$sucaiInfo->toArray();
        //已删除
        if($sucaiInfo['del']==1){
            throw new BusinessException(ErrorCode::ERROR, '素材已删除！');
        }
        //未正常通过
        if($sucaiInfo['status']!=3){
            throw new BusinessException(ErrorCode::ERROR, '素材暂时不能查看！');
        }
        //查询

//        //统计
//        $tongji=db('img')->where(['uid'=>$info['uid'],'del'=>0])->field("count(status) as num,status")->group('status')->select();
//        $arr=[];
//        $arr[0]=0;
//        $arr[1]=0;
//        $arr[2]=0;
//        $arr[3]=0;
//        if(!empty($tongji)){
//            foreach($tongji as $v){
//                if($v['status']==0){
//                    $arr[0]=$v['num'];
//                }elseif($v['status']==1){
//                    $arr[1]=$v['num'];
//                }elseif($v['status']==2){
//                    $arr[2]=$v['num'];
//                }elseif($v['status']==3){
//                    $arr[3]=$v['num'];
//                }
//            }
//        }
//        $this->assign('tongji',$arr);
//
//        //是否关注
//        if($this->uid==null){
//            $this->assign('guanuser',1);//未关注
//            $this->assign('shoucang',1);//未关注
//        }else{
//            $guanuser=db('guanzhuuser')->where(['uid'=>$this->uid,'bid'=>$info['uid']])->find();
//            if(empty($guanuser)){
//                $this->assign('guanuser',1);
//            }else{
//                $this->assign('guanuser',2);
//            }
//            //是否收藏
//            $shoucang=db('shouimg')->where(['uid'=>$this->uid,'iid'=>$id])->find();
//            if(empty($shoucang)){
//                $this->assign('shoucang',1);//未关注
//            }else{
//                $this->assign('shoucang',2);
//            }
//        }

//        //类型
//        $fenleiid=db('fenleirelation')->where(['iid'=>$id])->order('mid asc')->find();
//        $fenlei=db('fenlei')->where(['id'=>$fenleiid['mid']])->find();
//        $this->assign('fenlei',$fenlei);

//
//        //主题
////        $sciid=db('scthreefenleirelation')->where(['iid'=>$id])->order('mid asc')->find();
////        $scthemefenlei=db('scthreefenlei')->where(['id'=>$sciid['mid']])->find();
//        $this->assign('scthemefenlei',[]);
//
//        //获取格式
//        $geshi=db('geshirelation')->where(['iid'=>$id])->find();
//        $geshi=db('geshi')->where(['id'=>$geshi['mid']])->find();
//        $Sucaiajax=new Sucaiajax();
//        //$getsztj=$Sucaiajax->getsztj();
//        $this->assign('cainixihuan',[]);
//
//        //本类素材
//        //$thisfenleisucai=$Sucaiajax->thisfenleisucai($fenlei['mid'],0,0,15);//素材
//        $this->assign('total',30);
//        $this->assign('thisfenleisucai',[]);

//
//        //自己的
//        $thisfenleisucais=db('img')->where(['uid'=>$info['uid'],'status'=>3,'del'=>0])->order("rand()")->paginate(15);
//        $thisfenleisucais->toArray();
//        $arrIds=$fIdNameArr=[];
//        foreach ($thisfenleisucais as $key => $value) {
//            array_push($arrIds,$value['id']);
//        }
//        if(!empty($arrIds)){
//            $idstrs=implode(',',$arrIds);
//            $fnameArr= db('fenleirelation')
//                ->alias('r')
//                ->join("dczg_fenlei f","f.id=r.mid",'left')
//                ->where("r.iid in ({$idstrs})")
//                ->field('r.iid,f.name')->select();
//            if(!empty($fnameArr)){
//                foreach($fnameArr as $k=>$fname){
//                    $fIdNameArr[$fname['iid']]=$fname['name'];
//                }
//            }
//            foreach ($thisfenleisucais as $key => $value) {
//                $data = $value;
//                $data['fname']=isset($fIdNameArr[$value['id']])?$fIdNameArr[$value['id']]:'';
//                $thisfenleisucais->offsetSet($key,$data);
//            }
//        }
//        $this->assign('total',$thisfenleisucais->lastPage());
//        $this->assign('thisfenleisucais',$thisfenleisucais);
//
//        //浏览量
//        db('img')->where(['id'=>$id])->setInc('looknum');
//        //我的合集
//        //$myheji=db('heji')->where(['uid'=>$this->uid])->field('id,title')->select();
//        $this->assign('myheji',[]);
//       //推荐合集
//        //$list=(new Heji())->getheji(10,true,"rand()");
//        $this->assign('heji',[]);
//        $cache['heji']=[];
//
//        //广告位
//        //$guanggaowei=db('sucaiguanggao')->order("id desc")->select();
//        $this->assign('guanggaowei',[]);
//        $cache['guanggaowei']=[];
//
//        //关键词
//        $linglebel=array_unique(explode(' ', $info['guanjianci']));
//        foreach($linglebel as $k=>$v){
//            if(trim($v)==''){
//                unset($linglebel[$k]);
//            }
//        }
//        $this->assign('linglebel',$linglebel);

//        $userdata=db('userdata')->where(['uid'=>$info['uid']])->field('shoucang,zhuanji,zuopin,shouling,shousucai,shouwen,sucainum,wenkunum')->find();
//        $this->assign('userdata',$userdata
        return [];
    }

}
