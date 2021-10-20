<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\Album;
use App\Model\Albumlist;
use App\Model\Caiji;
use App\Model\Shouling;
use App\Model\Userdata;
use App\Repositories\BaseRepository;
use Hyperf\Contract\LengthAwarePaginatorInterface;
use Hyperf\Database\Model\Builder;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/*
 * 专辑库
 */

class AlbumRepository extends BaseRepository
{
    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    /**
     * 获取分页列表.
     */
    public function getList(mixed $queryData): LengthAwarePaginatorInterface
    {
        return Album::query()->where($queryData)->paginate();
    }

    /**
     * 自定义随机分页列表.
     */
    public function getListPageRand(mixed $queryData): array
    {
        $sql         = 'SELECT * FROM dczg_albumlist as l where l.del<=1 and (l.is_color=1 or l.color_id=1 or l.yid=0)';
        $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_albumlist)-(SELECT MIN(id) FROM dczg_albumlist)) + (SELECT MIN(id) FROM dczg_albumlist))) limit 0,40';
        $randListArr = Db::select($sql, []);
        //处理数据
        if (!empty($randListArr)) {
            foreach ($randListArr as $key => &$val) {
                $tmp['id']         = $val->id ?? 0;
                $tmp['path']       = get_img_path($val->path, ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']      = $val->title   ?? '';
                $tmp['looknum']    = $val->looknum ?? 0;
                $tmp['downnum']    = $val->downnum ?? 0;
                $tmp['dtime']      = $val->dtime   ?? 0;
                $randListArr[$key] = $tmp;
                $tmp               = [];
            }
        }
        return $randListArr;
    }

    /**
     * 模糊搜索灵感数据，包含标题和标签.
     *
     * @param $query
     *
     * @param $order
     * @param mixed $where
     *
     * @return mixed
     */
    public function searchAlbumList($query, $order, $where = [])
    {
        //return Albumlist::search()->where("title",$query)->paginate(200);
        //return Albumlist::search($query)->paginate(200);

        $queryArr = ['title' => ['or', "{$query}"], 'label' => ['or', "{$query}"]];
        $orm      = Albumlist::search($query, es_callback($queryArr));

        if (!empty($order)) {
            $orm = $orm->orderBy($order, 'desc');
        }

        if (!empty($where)) {
            $orm = $orm->where($where);
        }
        $list = $orm->paginateRaw(200)->toArray();
        $list = format_es_page_raw_data($list);
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
            foreach ($list['data'] as $key => &$val) {
                if (!isset($val['id']) || empty($val['title'])) {
                    unset($list['data'][$key]);
                    continue;
                }
                $tmp['id']          = $val['id'] ?? 0;
                $tmp['path']        = get_img_path($val['path'], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']       = $val['title']   ?? '';
                $tmp['looknum']     = $val['looknum'] ?? 0;
                $tmp['downnum']     = $val['downnum'] ?? 0;
                $tmp['dtime']       = $val['dtime']   ?? 0;
                $list['data'][$key] = $tmp;
                $tmp                = [];
            }
        }
        return $list;
    }

    /**
     * 获取专辑-专辑列表关联数据.
     */
    public function getAlbumDetailList(array $where, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 100;
        $orm      = Album::from('album as a')->join('albumlist as l', 'a.id', '=', 'l.aid', 'left')->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 灵感图片详细信息，中图展示页面，获取图片，专辑，用户等信息.
     */
    public function getDetail(array $where, array $column = ['a.name as album_name ', 'a.isoriginal', 'l.*', 'u.nickname', 'u.imghead'])
    {
        return Album::from('albumlist as l')->join('album as a', 'a.id', '=', 'l.aid', 'inner')
            ->join('user as u', 'u.id', '=', 'a.uid', 'inner')
            ->where($where)->select($column)->first();
    }

    /**
     * 获取专辑信息.
     */
    public function getAlbumDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Album::from('album as a')->join('albumlist as l', 'a.id', '=', 'l.aid', 'left')
            ->where($where)->select($column)->first();
    }

    /**
     * 获取专辑列表信息.
     */
    public function getAlbumListDetail(array $where, array $column = ['*']): Model|Builder|null
    {
        return Albumlist::query()->where($where)->select($column)->first();
    }

    /**
     * 获取专辑信息.
     */
    public function getAlbumDetailInfo(array $where, array $column = ['*']): Model|Builder|null
    {
        return Album::query()->where($where)->select($column)->first();
    }

    /**
     * 获取专辑列表信息.
     * @param mixed $query
     */
    public function getAlbumListDetailPage(array $where, $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 100;
        $orm      = Albumlist::query()->where($where);
        $count    = $orm->count();
        $list     = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 收藏灵感图片.
     *
     * @param mixed $albumlistInfo
     * @param mixed $albumInfo
     * @param $uid
     *
     * @param $remark
     */
    public function collectAlbumImg($albumlistInfo, $albumInfo, $uid, $remark): int|null
    {
        $shouLingInfo = Shouling::where(['uid' => user()['id'], 'lid' => $albumlistInfo['id']])->first();

        if (!empty($shouLingInfo)) {
            return $albumlistInfo['shoucang'];
        }

        Db::beginTransaction();
        $add             = [];
        $add['uid']      = $uid;
        $add['lid']      = $albumlistInfo['id'];
        $add['img_url']  = $albumlistInfo['path'];
        $add['album_id'] = $albumlistInfo['aid'];
        $add['img_uid']  = $albumInfo['uid'];
        $add['c_time']   = time();
        $add['remark']   = $remark;

        if (!Shouling::insert($add)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '收藏失败！');
        }
        //增加收藏次数
        if (!Albumlist::where(['id' => $albumlistInfo['id']])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计灵感一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->increment('shouling', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $albumInfo['uid'], $albumlistInfo['id'], 6)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Albumlist::where(['id' => $albumlistInfo['id']])->value('shoucang');
    }

    /**
     * 取消收藏灵感图片.
     *
     * @param mixed $albumlistInfo
     * @param mixed $albumInfo
     * @param $uid
     */
    public function deleteCollectAlbumImg($albumlistInfo, $albumInfo, $uid): int|null
    {
        $shouLingInfo = Shouling::where(['uid' => user()['id'], 'lid' => $albumlistInfo['id']])->first();

        if (empty($shouLingInfo)) {
            return $albumlistInfo['shoucang'];
        }

        Db::beginTransaction();

        if (!Shouling::where(['uid' => user()['id'], 'lid' => $albumlistInfo['id']])->delete()) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //减少收藏次数
        if (!Albumlist::where(['id' => $albumlistInfo['id']])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计你自己收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shoucang', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //统计灵感一共收藏了多少个
        if (!Userdata::where(['uid' => $uid])->decrement('shouling', 1)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }

        //增加日志
        if (!$this->waterDoRepository->addWaterDo($uid, $albumInfo['uid'], $albumlistInfo['id'], 10)) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        Db::commit();
        return Albumlist::where(['id' => $albumlistInfo['id']])->value('shoucang');
    }

    /**
     * 采集灵感图片.
     * @param mixed $albumlistInfo
     * @param mixed $albumInfo
     * @param mixed $albumlistInfoOld
     */
    public function captureAlbumImg($albumlistInfo, $albumInfo, $albumlistInfoOld): array|bool
    {
        $uid = user()['id'] ?? 0;
        unset($albumlistInfo['id']);
        $originAlbumList = Album::where(['id' => $albumlistInfoOld['aid']])->select(['id', 'uid', 'isoriginal'])->first();

        if (empty($originAlbumList)) {
            throw new BusinessException(ErrorCode::ERROR, '该图片无法采集！');
        }

        //判断是否该图片已经采集到该专辑里面了
        $aidlist = Albumlist::where(['aid' => $albumInfo['id'], 'cid' => $albumlistInfoOld['id']])->first();

        if (!empty($aidlist)) {
            throw new BusinessException(ErrorCode::ERROR, '该图片已经采集到该专辑，请勿重复采集！');
        }

        $originAlbumList = $originAlbumList->toArray();
        Db::beginTransaction();
        try {
            //如果没有采集过
            $caiJi = Caiji::query()->where(['cid' => $albumlistInfoOld['id'], 'uid' => $uid])->first();

            if (empty($caiJi)) {
                $add    = ['cid' => $albumlistInfoOld['id'], 'uid' => $uid, 'num' => 1];
                $lastId = Caiji::insert($add);

                if (!$lastId) {
                    throw new BusinessException(ErrorCode::ERROR, '操作失败,稍后重试！');
                }
            } else {
                $caiJi = $caiJi->toArray();
                //采集满5次了
                if ($caiJi['num'] >= 5) {
                    throw new BusinessException(ErrorCode::ERROR, '该图片你已经采集超过5次,无法完成采集,换一张试试！');
                }
                //增加你采集的数据
                if (!Caiji::where(['cid' => $albumlistInfoOld['id'], 'uid' => $uid])->increment('num', 1)) {
                    throw new BusinessException(ErrorCode::ERROR, '操作失败,稍后重试！');
                }
            }

            //修改我的专辑对应的状态，增加专辑的图片数量
            $albumInfoUpdateData['status'] = 2;
            $albumInfoUpdateData['num']    = $albumInfo['num'] + 1;

            if (!Album::where('id', $albumInfo['id'])->update($albumInfoUpdateData)) {
                throw new BusinessException(ErrorCode::ERROR, '3操作失败,稍后重试！');
            }

            //增加被采集的数量,采集时间
            $albumlistUpdateData['g_time'] = time();
            $albumlistUpdateData['dtime']  = time();
            $albumlistUpdateData['caiji']  = intval($albumlistInfo['caiji']) + 1;

            if (!Albumlist::where('id', $albumlistInfo['yid'])->update($albumlistUpdateData)) {
                throw new BusinessException(ErrorCode::ERROR, '4操作失败,稍后重试！');
            }

            //入库
            $albumlistInfo['caiji'] = 0;
            $albumlistLastId        = Albumlist::insertGetId($albumlistInfo);

            if (!$albumlistLastId) {
                throw new BusinessException(ErrorCode::ERROR, '操作失败,稍后重试！');
            }

            //增加采集增加日志
            if (!$this->waterDoRepository->addWaterDo($uid, $originAlbumList['uid'], $albumlistInfoOld['id'], 4, $albumInfo['id'], $originAlbumList['isoriginal'], $originAlbumList['id'])) {
                throw new BusinessException(ErrorCode::ERROR, '操作失败,稍后重试！');
            }

            //修改专辑4张预览图片
            $this->updateAlbumPreviewImgs($albumInfo['id']);

            Db::commit();
            return ['id' => $albumlistLastId];
        } catch (\Throwable $ex) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, $ex->getMessage());
        }
    }

    /**
     * 更新album表中预览图字段preview_imgs.
     * @param mixed $aid
     */
    public function updateAlbumPreviewImgs($aid): int
    {
        //根据专辑ID查询到该专辑中采集数量前4的图片
        $previewImgs = Albumlist::where('aid', $aid)->orderBy('caiji', 'desc')->limit(4)->pluck('path')->toArray();

        if (empty($previewImgs)) {
            $previewImgs = [];
        }
        //更新字段
        return Album::where('id', $aid)->update(['preview_imgs' => json_encode($previewImgs)]);
    }

    /**
     * 搜索album表中展示专辑.
     */
    public function getAlbum(string $where = '', string $order = 'daytime desc,id desc'): array
    {
        //获取过滤禁用展示的用户id
        $blockIds = $this->getBlockAlbumIdsByUser();

        if (!empty($blockIds)) {
            $blockIdsStr = implode(',', $blockIds);
            $where       = $where . ' id not in (' . $blockIdsStr . ')';
        }

        //默认排序
        if(empty($order)){
          $order= 'daytime desc,id desc';
        }
        $list=Album::whereRaw($where)->orderByRaw($order)->paginate(30)->toArray();
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
          foreach ($list['data'] as $key => &$val) {
            if (!isset($val['id'])) {
              unset($list['data'][$key]);
              continue;
            }
            //预览图
            if(!empty($val['preview_imgs'])){
              $val['preview_imgs']=json_decode($val['preview_imgs'],true);
            }
            //循环判断是否有封面图
            if($val['fengmian']!=''){
              $tmp['fengmian']=get_img_path($val['fengmian'], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
            }else{
              //从预览图里面获取
              if(!empty($val['preview_imgs'])&& isset($val['preview_imgs'][0])){
                $tmp['fengmian']=get_img_path($val['preview_imgs'][0], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
              }else{
                $tmp['fengmian']='';
              }
            }
            if(!empty($val['preview_imgs']) && is_array($val['preview_imgs'])){
              foreach ($val['preview_imgs'] as &$v){
                $tmp['preview_imgs'][]=get_img_path($v, ImgSizeStyle::ALBUM_LIST_PREVIEW_PIC);
              }
            }else{
              $tmp['preview_imgs']=[];
            }
            $tmp['id']          = $val['id'] ?? 0;
            $tmp['name']       = $val['name']   ?? '';
            $tmp['num']     = $val['num'] ?? 0;
            $tmp['looknum']     = $val['looknum'] ?? 0;
            $tmp['guanzhu']     = $val['guanzhu'] ?? 0;
            $tmp['g_time']       = $val['g_time']   ?? 0;
            $list['data'][$key] = $tmp;
            $tmp                = [];
          }
        }
      return $list;
    }
    /**
     * 搜索album表中展示专辑.
     */
    public function getAlbumListById(int $id): array
    {
        $list = Albumlist::where('aid',$id)->where('del','<=','1')->select(['id','title','path','looknum','downnum','dtime'])->paginate(20)->toArray();
        //处理数据
        if (!empty($list) && isset($list['data']) && !empty($list['data'])) {
            foreach ($list['data'] as $key => &$val) {
                if (!isset($val['id']) || empty($val['title'])) {
                    unset($list['data'][$key]);
                    continue;
                }
                $tmp['id']          = $val['id'] ?? 0;
                $tmp['path']        = get_img_path($val['path'], ImgSizeStyle::ALBUM_LIST_SMALL_PIC);
                $tmp['title']       = $val['title']   ?? '';
                $tmp['looknum']     = $val['looknum'] ?? 0;
                $tmp['downnum']     = $val['downnum'] ?? 0;
                $tmp['dtime']       = $val['dtime']   ?? 0;
                $list['data'][$key] = $tmp;
                $tmp                = [];
            }
        }
        return $list;
    }
}
