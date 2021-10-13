<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ImgSizeStyle;
use App\Model\Picture;
use App\Model\Wenku;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;

/**
 * 文库.
 */
class WenkuRepository extends BaseRepository
{
    /**
     * 获取文库信息.
     */
    public function getLibraryStatistics(array $where): Collection|array
    {
        return Wenku::query()->where($where)->groupBy(['status'])->select(Db::raw('count(status) as count'), 'status')->get()
            ->toArray();
    }

    /**
     * 获取文库信息列表.
     */
    public function getLibraryList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $orm      = Wenku::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    public function getLibraryDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Wenku::query()->where($where)->select($column)->first();
    }

    /**
     * 获取文库信息列表，待筛选展示.
     *
     * @param  array  $query
     *
     * @return array
     */
    public function getSearchWenkuList(array $query): array
    {

        $where    = ['status' => 3, 'del' => 0];
        //排序
        if(empty($query['order'])){
            $order="w.dtime";
        }else{
            $order='w.'.$query['order'];
        }
        //类型 1共享，2原创
        if(empty($query['lid'])){
            $where['w.leixing']=$query['lid'];
        }
        //类型 1共享，2原创
        if(empty($query['w.title'])){
            $where['w.title']=$query['lid'];
        }

        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 20;
        $orm = Db::table('wenku as w')->leftJoin('user as u','u.id','=','w.uid')->where($where);
        //$orm      = Wenku::query()->leftJoin('user as u','u.id','=','w.uid')->where($where);
        $count    = $orm->count();
        $list     = $orm->select("w.*,u.nickname as username")->orderBy($order, 'desc')->orderBy('w.id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
        foreach ($list as $k=> $v){
            $data = $v;
            if(!empty($v['img'])){
                $pdfimg=$this->getPicturejson($v['img']);
                $data['pdfimg']=$pdfimg;
            }else{
                $data['pdfimg']='/'.$v['pdfimg'];
            }
            $list[$k]=$data;
        }
        return ['count' => $count, 'list' => $list];
    }


}
