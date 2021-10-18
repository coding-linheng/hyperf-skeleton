<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * BaseRepository.php.
 *
 * 仓库基类
 *
 * User：YM
 * Date：2019/11/21
 * Time：下午2:36
 */

namespace App\Repositories;

use App\Constants\ImgSizeStyle;
use App\Constants\StatusCode;
use App\Model\Album;
use App\Model\Picture;
use App\Repositories\V1\UserRepository;
use App\Task\Producer\CachePlanProducer;
use http\Client\Curl\User;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * BaseRepository
 * 仓库基类.
 */
class BaseRepository
{
    /**
     * @Inject
     */
    protected ContainerInterface $container;

    /**
     * Created by PhpStorm.
     * 可以实现自动注入的业务容器
     * User：YM
     * Date：2020/1/12
     * Time：上午8:18.
     */
    protected array $businessContainerKey = ['auth', 'adminPermission'];

    protected $dbPictureCacheKey = 'db_picture';

    #[Inject]
    protected UserRepository $userRepository;
    /**
     * __get
     * 隐式注入服务类
     * User：YM
     * Date：2019/11/21
     * Time：上午9:27.
     * @param $key
     * @return \Psr\Container\ContainerInterface|void
     */
    public function __get($key)
    {
        if ($key == 'app') {
            return $this->container;
        }

        if (in_array($key, $this->businessContainerKey)) {
            return $this->getBusinessContainerInstance($key);
        }

        if (str_ends_with($key, 'Service')) {
            return $this->getServiceInstance($key);
        }
        throw new \RuntimeException("服务{$key}不存在，书写错误！", StatusCode::ERR_SERVER);
    }

    /**
     * getBusinessContainerInstance
     * 获取业务容器实例
     * User：YM
     * Date：2020/1/12
     * Time：上午8:15.
     * @param $key
     */
    public function getBusinessContainerInstance($key): mixed
    {
        $key       = ucfirst($key);
        $fileName  = BASE_PATH . "/app/Core/Common/Container/{$key}.php";
        $className = "Core\\Common\\Container\\{$key}";

        if (file_exists($fileName)) {
            return $this->container->get($className);
        }
        throw new \RuntimeException("通用容器{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }

    /**
     * getServiceInstance
     * 获取服务类实例
     * User：YM
     * Date：2019/11/21
     * Time：上午10:30.
     * @param $key
     */
    public function getServiceInstance($key): mixed
    {
        $key       = ucfirst($key);
        $fileName  = BASE_PATH . "/app/Core/Services/{$key}.php";
        $className = "Core\\Services\\{$key}";

        if (file_exists($fileName)) {
            return $this->container->get($className);
        }
        throw new \RuntimeException("服务{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }

    /*======================= 以下为各仓库公用函数部分=============================================*/

    //获取详情图片地址
    public function getImgsUrl($imgs, $suffix = ImgSizeStyle::ALBUM_LIST_SMALL_PIC)
    {
        $pictures   = json_decode($imgs, true);
        $pictureTmp = [];

        if (is_array($pictures)) {
            foreach ($pictures as $pid) {
                $pictureTmp[] = $this->getPictureById($pid, $suffix);
            }
        }
        return $pictureTmp;
    }

    //根据id查询图片地址
    public function getPictureById($imgId, $suffix = ImgSizeStyle::ALBUM_LIST_SMALL_PIC)
    {
        $path = $this->getPictureUrlById($imgId);

        if (empty($path)) {
            return '';
        }
        return get_img_path($path, $suffix);
    }

    //根据id查询图片地址
    public function getPictureUrlById($imgId)
    {
        if (empty($imgId)) {
            return '';
        }
        $redis  = redis('cache');
        $keyUrl = $redis->zRangeByScore($this->dbPictureCacheKey, (string)$imgId, (string)$imgId);

        if (!empty($keyUrl) && count($keyUrl) > 0) {
            return $keyUrl[0];
        }
        $path = Picture::query()->where(['id' => $imgId])->first();

        if (empty($path)) {
            return '';
        }
        $redis->zAdd($this->dbPictureCacheKey, $imgId, $path->url);
        return $path->url;
    }

    //查询json字符串 查询图片数据
    public function getPictureJson($imgStr, $suffix = ImgSizeStyle::ALBUM_LIST_SMALL_PIC): string
    {
        if (empty($imgStr)) {
            return '';
        }
        $img = json_decode($imgStr, true);

        if (empty($img) || count($img) < 1) {
            return '';
        }
        $redis = redis('cache');
        //从未缓存或者缓存条数少于20000都会触发一次全部缓存
        if (!$redis->exists([$this->dbPictureCacheKey]) || $redis->zCard($this->dbPictureCacheKey) < 20000) {
            if ($redis->setnx('lockPictureTmp', 1)) {
                $redis->expire('lockPictureTmp', 30);
                $this->rePushPicToRedis($redis);
            }
        }
        $keyUrl = $redis->zRangeByScore($this->dbPictureCacheKey, (string)$img[0], (string)$img[0]);

        if (!empty($keyUrl) && count($keyUrl) > 0) {
            return get_img_path($keyUrl[0], $suffix);
        }
        /** @var Picture $path */
        $path = Picture::query()->where(['id' => $img[0]])->first();

        if (empty($path)) {
            return '';
        }
        $redis->zAdd($this->dbPictureCacheKey, $img[0], $path->url);
        return get_img_path($path->url, $suffix);
    }

    //缓存所有预览图到redis
    public function rePushPicToRedis($redis)
    {
        //判断是否存在zset key db_picture
        $redis->expire($this->dbPictureCacheKey, 3600 * 7 * 24);
        //使用异步队列跑
        $cacheProducerTask = di()->get(CachePlanProducer::class);
        //将请求日志推入异步队列记录入库
        echo 'push to cache queue!';
        $cacheProducerTask->cachePicture(['cache_key' => $this->dbPictureCacheKey], 0);
    }

    //todo 方法待改进获取周数
    public function getweek()
    {
        $time    = time();
        $strtime = strtotime(date('2019-7-22'));
        $i       = 1;
        while (true) {
            $endtime = $strtime + 7 * 86400; //周

            if ($time >= $strtime && $time < $endtime) {
                return $i;
                break;
            }
            $strtime = $endtime;
            ++$i;
        }
    }
    public function getBlockAlbumIdsByUser(){
          $blockUserList=$this->userRepository->blockAlbumUser();
          $returnIds = $zjList=$ycZjList=[];
          if(!empty($blockUserList)){
              foreach ($blockUserList as $val){
                  if($val['iszj']==2){
                      $zjList = Album::query()->where(['uid'=>$val['id'],'isoriginal'=>1])->pluck('id');
                      if(!empty($zjList)){
                          $returnIds=$zjList;
                      }
                  }
                  if($val['isyczj']==2){
                      $ycZjList = Album::query()->where(['uid'=>$val['id'],'isoriginal'=>2])->pluck('id');
                  }
              }
          }

        if(!empty($ycZjList) && !empty($returnIds)){
            $returnIds=array_merge($ycZjList,$returnIds);
        }
        if(!empty($ycZjList) && empty($returnIds)){
            $returnIds=$ycZjList;
        }
        return $returnIds;
    }
}
