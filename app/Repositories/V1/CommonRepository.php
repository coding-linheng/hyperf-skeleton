<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Advertisement;
use App\Model\Bannerindex;
use App\Model\Blogroll;
use App\Model\Top;
use App\Repositories\BaseRepository;
use Hyperf\DbConnection\Db;

/**
 * RequestLogRepository.
 */
class CommonRepository extends BaseRepository
{
    public function getBannerIndex(): array
    {
        $bannerIndex = Bannerindex::query()->orderByDesc('id')->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if (!empty($bannerIndex)) {
            foreach ($bannerIndex as $key => &$val) {
                $url = $this->getPictureUrlById($val['img']);

                if (!empty($url)) {
                    $bannerIndex[$key]['img'] = get_img_path_private($url);
                }
            }
        }
        return $bannerIndex;
    }

    /**
     * 推荐用户.
     *
     * @param $type
     */
    public function getRecommendUserList($type): array
    {
        if ($type == 2) {
            $where = 'ud.album_tui=1';
        } else {
            $where = 'ud.sucai_tui=1';
        }
        $sql         = 'SELECT u.username, u.nickname,u.imghead,ud.shoucang,zhuanji,zuopin,sucainum,wenkunum FROM dczg_user as u inner join dczg_userdata as ud on u.id=ud.uid where ' . $where;
        $sql         .= ' order by rand()  limit 0,10';
        return Db::select($sql, []);
    }

    /**
     * 推荐作品
     * @param mixed $type
     */
    public function getRecommendZpList($type): array
    {
        $return = [];
        //获取推荐作品
        if ($type == 2) {
            //获取推荐灵感图片
            $sql         = 'SELECT * FROM dczg_album as l where l.del<=1 and l.status=2 and l.tui=2 and fengmian!=""';
            $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_album)-(SELECT MIN(id) FROM dczg_album)) + (SELECT MIN(id) FROM dczg_album))) limit 0,10';
            $return = Db::select($sql, []);
            //处理数据
            if (!empty($return)) {
                foreach ($return as $key => &$val) {
                    $tmp['id']            = $val->id ?? 0;
                    $tmp['img_url']       = get_img_path_private($val->fengmian);
                    //循环判断是否有封面图
                    if ($val->fengmian != '') {
                        $tmp['img_url']       = get_img_path_private($val->fengmian);
                    } else {
                        //从预览图里面获取
                        if (!empty($val->preview_imgs)) {
                            $previewImgs    = json_decode($val->preview_imgs, true);
                            $tmp['img_url'] = isset($previewImgs[0]) ? get_img_path_private($previewImgs[0]) : '';
                        } else {
                            $tmp['img_url'] = '';
                        }
                    }
                    $return[$key]      = $tmp;
                    $tmp               = [];
                }
            }
        } else {
            //获取推荐素材图片
            $sql         = "SELECT * FROM dczg_img where del<1 and status=3 and tui=2 and img!=''";
            $sql         .= ' and id >= (SELECT floor( RAND() * ((SELECT MAX(id) FROM dczg_img)-(SELECT MIN(id) FROM dczg_img)) + (SELECT MIN(id) FROM dczg_img))) limit 0,10';
            $return = Db::select($sql, []);
            //处理数据
            if (!empty($return)) {
                foreach ($return as $key => &$val) {
                    $tmp['id']         = $val->id ?? 0;
                    $img               = json_decode($val->img, true);

                    if (empty($img) || count($img) < 1) {
                        $tmp['img_url'] = '';
                    } else {
                        $tmp['img_url']     = get_img_path_private($this->getPictureUrlById($img[0]));
                    }
                    $return[$key]      = $tmp;
                    $tmp               = [];
                }
            }
        }
        return $return;
    }

    public function getAdvertisement()
    {
        $advertisement = Advertisement::query()->orderByDesc('id')->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if (!empty($advertisement)) {
            foreach ($advertisement as $key => &$val) {
                $url = $this->getPictureUrlById($val['img']);

                if (!empty($url)) {
                    $advertisement[$key]['img'] = get_img_path_private($url);
                }
            }
        }
        return $advertisement;
    }

    public function getIndexTopAdvertisement()
    {
        $topAdvertisement = Top::query()->orderByDesc('id')->get()->toArray();
        //做个判断从数据库中查询出图片的具体路径然后缓存
        if (!empty($topAdvertisement)) {
            for ($i = 2; $i <= 10; ++$i) {
                $url = $this->getPictureUrlById($topAdvertisement['img' . $i]);

                if (!empty($url)) {
                    $topAdvertisement['img' . $i] = get_img_path_private($url);
                }
            }
        }
        return $topAdvertisement;
    }

    //友情链接
    public function getBlogRoll()
    {
        //友情链接
        $blogRoll = Blogroll::query()->where(['status' => 1])->orderBy('sort')->get()->toArray();

        foreach ($blogRoll as $k => $v) {
            if ($v['img_id'] != 0) {
                //将id 转成图片缓存
                $url = $this->getPictureUrlById($v['img_id']);

                if (!empty($url)) {
                    $blogRoll[$k]['img'] = get_img_path_private($url);
                } else {
                    return '/static/module/admin/img/onimg.png';
                }
            }
        }
        return $blogRoll;
    }
}
