<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Repositories\V1\AlbumRepository;
use App\Repositories\V1\SucaiRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WenkuRepository;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;

/**
 * UserService
 * 用户中心相关逻辑.
 */
class PersonalHomePageService extends BaseService
{
    #[Inject]
  protected UserRepository $userRepository;

    #[Inject]
  protected SucaiRepository $sucaiRepository;

    #[Inject]
  protected WenkuRepository $wenkuRepository;

    #[Inject]
  protected AlbumRepository $albumRepository;

    /**
     * 个人主页.
     * @param mixed $uid
     */
    public function homePage($uid): Model
    {
        $field = [
            'u.id', 'u.nickname', 'u.imghead', 'u.content', 'u.wx',
            'u.money', 'u.qi', 'u.fans', 'u.guan', 'u.isview', 'd.shoucang', 'd.zhuanji', 'd.zuopin', 'd.sucainum', 'd.wenkunum', 'd.cover_img','d.cover_img_tmp', 'd.cover_img_msg', 'd.cover_img_status'
        ];

        return $this->userRepository->getUserMerge($uid, $field);
    }

    /**
     * 获取某个用户的列表粉丝列表.
     * @param mixed $uid
     */
    public function fansListByUid($uid): array
    {
        $field = [
            'u.id', 'u.nickname', 'u.imghead', 'u.content', 'u.wx',
            'u.money', 'u.qi', 'u.fans', 'u.guan', 'u.isview', 'd.shoucang', 'd.zhuanji', 'd.zuopin', 'd.sucainum', 'd.wenkunum',
        ];
        //获取该用户关注的列表关联用户表，userdata表联合查询数据
        $fansList = $this->userRepository->getFansList($uid, $field);

        if (!empty($fansList) && isset($fansList['data']) && !empty($fansList['data'])) {
            foreach ($fansList['data'] as $key => &$val) {
                if (!isset($val['id'])) {
                    unset($fansList['data'][$key]);
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
                $fansList['data'][$key]  = $tmp;
            }
        }
        return $fansList;
    }

    /**
     * 获取某个用户的素材列表.
     */
    public function sucaiListByUid(mixed $uid): array
    {
        return $this->sucaiRepository->searchImgList('', [], ['uid' => $uid], '', 200);
    }

    /**
     * 获取某个用户的专辑列表.
     */
    public function albumListByUid(mixed $uid): array
    {
        $where = 'del=1 and uid=' . $uid;
        return $this->albumRepository->getAlbum($where, '');
    }

    /**
     * 获取某个用户的文库列表.
     */
    public function wenkuListByUid(mixed $uid): array
    {
        return $this->wenkuRepository->getSearchWenkuList(['uid' => $uid]);
    }

    /**
     * 获取某个用户的关注的用户列表.
     */
    public function followListByUid(mixed $uid): array
    {
        return $this->userRepository->followListByUid($uid);
    }

    /**
     * 获取某个用户的邀请的用户列表.
     */
    public function inviteListByUid(mixed $uid): array
    {
        return $this->userRepository->inviteListByUid($uid);
    }

    /**
     * 修改封面.
     * @param :uid 用户id
     * @param :type 1使用默认，2自定义上传
     * @param :file 文件上传，只支持格式，png,jpg,jpeg格式
     */
    public function changeBackground(mixed $uid,$type,$data): array
    {
        if(empty($uid) || $uid!=user()['id']){
            throw new BusinessException(ErrorCode::ERROR, '不能修改别人的主页！');
        }

        $res= $this->userRepository->changeBackground($uid,$type,$data);
        if(!$res){
            throw new BusinessException(ErrorCode::ERROR, '操作失败！');
        }
        return $data;
    }

    /**
     * 获取某个用户的收藏列表.
     * @param :uid 用户id;
     * @param :type 类型 1素材，2专辑，3文库
     * @param mixed $type
     */
    public function collectListByUid(int $uid, $type): array
    {
        switch ($type) {
      case 1:
        //1素材
        $list = $this->sucaiRepository->getCollectSucaiImgListByUid($uid);
        break;
      case 2:
        //2专辑
        $list = $this->albumRepository->getCollectAlbumList($uid);
        break;
      case 3:
        //3文库
        $list = $this->wenkuRepository->getShouCangList($uid);
        break;
      default:
        $list = [];
        break;
    }
        return $list;
    }
}
