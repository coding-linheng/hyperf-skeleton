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
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id','img.del','img.status', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci',  'shoucang']);

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
        $sucaiInfo = $this->sucaiRepository->getSucaiImgDetailInfo(['img.id' => $id], ['img.id','img.del','img.status', 'uid', 'suffix', 'size', 'height', 'name', 'path', 'title', 'guanjianci', 'shoucang']);

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
}
