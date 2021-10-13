<?php

declare(strict_types=1);

namespace App\Task\Consumer\CachePlan\Handler;

use App\Model\Picture;

class CachePicture
{
    public function __invoke(array $data): bool
    {
        try {
            echo "=================start do CachePicture!".json_encode($data);
            if(isset($data['cache_key']) && !empty($data['cache_key'])){
                $cacheKey=$data['cache_key'];
            }else{
                $cacheKey='db_picture';
            }
            $redis = redis('cache');
            //循环查询，放入redis key 里面
            $page=0;
            $pageSize=2000;
            $total=Picture::query()->count();
            $totalPage=intval($total/$pageSize)+1;
            for ($page=1;$page<$totalPage;$page++){
                $picture=Picture::query()->select(['id','url'])->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
                if(!empty($picture)){
                    foreach ($picture as $v){
                        $redis->zAdd($cacheKey,$v['id'],$v['url']);
                    }
                }else{
                    if ($page>$totalPage-4){
                        break;
                    }
                }
            }

        } catch (\Throwable $exception) {
            echo $exception->getMessage();
        }
        return true;
    }
}
