<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Core\Repositories\V1\AlbumRepository;
use App\Model\Member;
use Psr\Http\Message\ResponseInterface;
use Hyperf\Di\Annotation\Inject;
/*
 * 专辑以及专辑列表相关操作
 */

class AlbumController extends AbstractController
{
  #[Inject]
  protected AlbumRepository $albumRepo;
    /**
     * 获取专辑列表.
     */
    public function getList(): ResponseInterface
    {
        $data= $this->albumRepo->getListPageRand([]);
        return $this->response->success($data);
    }

    /**
     * 搜索关键字专辑列表.
     */
    public function searchList(): ResponseInterface
    {
        return $this->response->success();
    }

    /**
     * 获取搜索详情.
     */
    public function getDetail(): ResponseInterface
    {
        return $this->response->success();
    }
}
