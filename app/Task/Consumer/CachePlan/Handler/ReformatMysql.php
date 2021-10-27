<?php

declare(strict_types=1);

namespace App\Task\Consumer\CachePlan\Handler;

use App\Model\Album;
use App\Model\Albumlist;
use App\Model\Geshirelation;
use App\Model\Img;
use App\Model\Mulurelation;
use App\Model\Picture;

class ReformatMysql
{
    public function __invoke(array $data): bool
    {
        try {
            $this->doAlbumTable();
            $this->doImgTable();
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
        return true;
    }

    //处理专辑表中格式，preview_img 四张预览图，分类id
    private function doAlbumTable()
    {
        try {
            //循环查询，放入redis key 里面
            $pageSize  = 2000;
            $total     = Picture::query()->count();
            $totalPage = intval($total / $pageSize) + 1;
            for ($page = 1; $page < $totalPage; ++$page) {
                $imgArr = Album::query()->select(['id', 'num', 'preview_imgs'])->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();

                if (!empty($imgArr)) {
                    foreach ($imgArr as $v) {
                        //查看该专辑是否有数量，如果有数量则执行获取前4个图片做缩略图
                        if (empty($v['num']) || $v['num'] = 0) {
                            continue;
                        }
                        $preView = [];

                        if (empty($v['preview_imgs'])) {
                            $preView = json_decode($v['preview_imgs'], true);
                        }

                        if (count($preView) == 4) {
                            continue;
                        }
                        //根据专辑ID查询到该专辑中采集数量前4的图片
                        $previewImgs = Albumlist::where('aid', $v['id'])->orderBy('caiji', 'desc')->limit(4)->pluck('path')->toArray();

                        if (empty($previewImgs)) {
                            $previewImgs = [];
                        }
                        //更新字段
                        Album::where('id', $v['id'])->update(['preview_imgs' => json_encode($previewImgs)]);
                    }
                } else {
                    if ($page > $totalPage - 4) {
                        break;
                    }
                }
            }
        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
    }

    //处理素材表中格式，分类id
    private function doImgTable()
    {
        try {
            $pageSize  = 2000;
            $total     = Img::query()->count();
            $totalPage = intval($total / $pageSize) + 1;
            for ($page = 1; $page < $totalPage; ++$page) {
                $imgArr = Img::query()->select(['id', 'mulu_id', 'geshi_id'])->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();

                if (!empty($imgArr)) {
                    $id = 0;

                    foreach ($imgArr as $v) {
                        //处理目录id
                        $midInfo = Mulurelation::query()->where('iid', $v['id'])->first();
                        $mid     = 0;

                        if (!empty($midInfo)) {
                            $mid = $midInfo->mid;
                        }
                        //处理格式id
                        $geShiInfo = Geshirelation::query()->where('iid', $v['id'])->first();
                        $gid       = 0;

                        if (!empty($geShiInfo)) {
                            $gid = $geShiInfo->mid;
                        }
                        Img::query()->where('id', $v['id'])->update(['mulu_id' => $mid, 'geshi_id' => $gid]);
                        $id = $v['id'];
                    }
                    echo '本次处理完：' . count($imgArr) . '条数据,处理到ID：' . $id;
                } else {
                    if ($page > $totalPage - 1) {
                        echo '处理完完成Img表！';
                        break;
                    }
                }
            }
        } catch (\Throwable $exception) {
            echo '处理Img表出错：' . $exception->getMessage();
        }
    }
}
