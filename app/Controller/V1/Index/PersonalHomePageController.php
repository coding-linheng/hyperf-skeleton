<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Constants\ErrorCode;
use App\Controller\AbstractController;
use App\Services\AlbumService;
use App\Services\PersonalHomePageService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 收藏专辑以及专辑图片相关操作
 */

class PersonalHomePageController extends AbstractController
{
    #[Inject]
  protected AlbumService $albumService;

    #[Inject]
  protected PersonalHomePageService $personalPageService;

    /**
     * 个人主页统计信息.
     * {"code":0,"msg":"success","data":{"id":3,"nickname":"啊实打实","imghead":"https:\/\/image.codelin.ink\/public\/uploads\/5195ca1a3342bdce549382dcf2b89879.jpg","content":"乱简介","wx":"123123123","money":"99799.00","qi":0,"fans":0,"guan":0,"isview":20,"shoucang":25,"zhuanji":132,"zuopin":153,"sucainum":0,"wenkunum":0}}.
     */
    public function homePage(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
        }
        return $this->success($this->personalPageService->homePage((int)$uid));
    }

    /**
     * 获取某个用户的列表粉丝列表.
     */
    public function fansListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
        }
        return $this->success($this->personalPageService->fansListByUid((int)$uid));
    }

    /**
     * 获取某个用户的专辑列表.
     */
    public function albumListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
        }
        return $this->success($this->personalPageService->albumListByUid((int)$uid));
    }

    /**
     * 获取某个用户的素材列表.
     * @param :uid 用户id;
     * @param :page 页面
     */
    public function sucaiListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
        }
        return $this->success($this->personalPageService->sucaiListByUid((int)$uid));
    }

    /**
     * 获取某个用户的文库列表.
     */
    public function wenkuListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '未找到用户');
        }
        return $this->success($this->personalPageService->wenkuListByUid((int)$uid));
    }

    /**
     * 获取某个用户的收藏列表.
     * @param :uid 用户id;
     * @param :type 类型 1素材，2专辑，3文库
     */
    public function collectListByUid(): ResponseInterface
    {
        $uid  = $this->request->input('uid', 0);
        $type = $this->request->input('type', 0);

        if (empty($uid) || empty($type)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '参数缺失');
        }
        return $this->success($this->personalPageService->collectListByUid((int)$uid, $type));
    }

    /**
     * 获取某个用户的关注的用户列表.
     * @param :uid 用户id;
     */
    public function followListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '用户未找到！');
        }
        return $this->success($this->personalPageService->followListByUid((int)$uid));
    }

    /**
     * 获取某个用户的关注的用户列表.
     * @param :uid 用户id;
     */
    public function inviteListByUid(): ResponseInterface
    {
        $uid = $this->request->input('uid', 0);

        if (empty($uid)) {
            $this->response->error(ErrorCode::VALIDATE_FAIL, '用户未找到！');
        }
        return $this->success($this->personalPageService->inviteListByUid((int)$uid));
    }

    /**
     * 获取收藏该专辑的设计师列表.
     */
    public function getDesignerByCollectAlbum(): ResponseInterface
    {
        return $this->response->success();
    }

    /**
     * 获取收藏该图片的设计师列表.
     */
    public function getDesignerByCollectImg(): ResponseInterface
    {
        return $this->response->success();
    }
}
