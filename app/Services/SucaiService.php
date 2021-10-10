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
}
