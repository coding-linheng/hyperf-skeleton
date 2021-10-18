<?php

/** @noinspection PhpUnnecessaryLocalVariableInspection */

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Constants\ErrorCode;
use App\Constants\ImgSizeStyle;
use App\Exception\BusinessException;
use App\Model\Daywaterdc;
use App\Model\Guanzhuuser;
use App\Model\Monthwaterdc;
use App\Model\Notice;
use App\Model\Picture;
use App\Model\Tixian;
use App\Model\User;
use App\Model\Userdata;
use App\Model\Uservip;
use App\Model\Waterdc;
use App\Model\Waterdo;
use App\Model\Waterscore;
use App\Model\Weekwaterdc;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Model;
use Hyperf\Redis\RedisProxy;

/**
 * 用户库.
 */
class UserRepository extends BaseRepository
{
    private ?RedisProxy $redis;

    public function __construct()
    {
        $this->redis = redis('cache');
    }

    /**
     * 获取用户data模型.
     * @param mixed $hideArr
     */
    public function getUserData(int $userid, $hideArr = []): Userdata
    {
        /** @var Userdata $userData */
        $userData = Userdata::query()->where('uid', $userid)->first();

        if (empty($userData)) {
            throw new BusinessException(ErrorCode::ERROR, '用户不存在');
        }

        if (!empty($hideArr)) {
            foreach ($hideArr as $col) {
                if (isset($userData->{$col})) {
                    unset($userData->{$col});
                }
            }
        }
        return $userData;
    }

    /**
     * 获取User用户.
     */
    public function getUser(int $userid): User
    {
        /** @var User $user */
        $user = User::query()->where('id', $userid)->first();

        if (empty($user)) {
            throw new BusinessException(ErrorCode::ERROR, '用户不存在');
        }
        return $user;
    }

    public function getUserMerge(int $userid, $column = ['*']): Model
    {
        return User::from('user as u')->join('userdata as d', 'u.id', '=', 'd.uid')
            ->where('u.id', $userid)
            ->select($column)->first();
    }

    /**
     * 获取今日收益.
     */
    public function todayIncome(int $userid): ?string
    {
        $time = strtotime('today');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /**
     * 获取昨日收益.
     */
    public function yesterdayIncome(int $userid): ?string
    {
        $time = strtotime('yesterday');
        return Daywaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取本周收益
     */
    public function thisWeekIncome(int $userid): ?string
    {
        $time = strtotime('this week sunday');
        return Weekwaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取上周周收益
     */
    public function lastWeekIncome(int $userid): ?string
    {
        $time = strtotime('last week sunday');
        return Weekwaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取本月收益
     */
    public function thisMonthIncome(int $userid): ?string
    {
        $time = strtotime(date('Y-m-d', strtotime('first day of this month')));
        return Monthwaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取上月收益
     */
    public function lastMonthIncome(int $userid): ?string
    {
        $time = strtotime(date('Y-m-d', strtotime('first day of last month')));
        return Monthwaterdc::query()->where('uid', $userid)->where('time', $time)->value('dc');
    }

    /*
     * 获取资金记录
     */
    public function getMoneyLog(int $userid, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['uid', '=', $userid]];

        if (isset($query['start_time'])) {
            $where[] = ['w.time', '>=', strtotime($query['start_time'])];
        }

        if (isset($query['end_time'])) {
            $where[] = ['w.time', '<', strtotime('+1 day', strtotime($query['end_time']))];
        }
        $orm   = Waterdc::from('waterdc as w')->join('user as u', 'u.id', 'w.bid')->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list];
    }

    /*
     * 获取共享分记录
     */
    public function getScoreLog(int $userid, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['uid', '=', $userid], ['w.score', '<>', '0']];

        if (isset($query['start_time'])) {
            $where[] = ['w.time', '>=', strtotime($query['start_time'])];
        }

        if (isset($query['end_time'])) {
            $where[] = ['w.time', '<', strtotime('+1 day', strtotime($query['end_time']))];
        }
        $orm   = Waterscore::from('waterscore as w')->join('user as u', 'u.id', 'w.bid')->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list];
    }

    /*
     * 获取提现记录
     */
    public function getCashLog(int $userid, int $page, int $pageSize, array $column = ['*']): array
    {
        $orm   = Tixian::query()->where('uid', $userid);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list];
    }

    /*
     * 获取私信
     */
    public function getPrivateMessage(int $userid, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['pid', '=', $userid]];

        $orm   = Notice::query()->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /*
     * 获取系统公告
     */
    public function getSystemMessage(array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['pid', '=', 0]];

        $orm   = Notice::query()->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')->offset(($page - 1) * $pageSize)->limit($pageSize)->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }

    /**
     * 获取消息内容.
     */
    public function getMessageDetail(int $noticeId): array
    {
        return Notice::query()->where('id', $noticeId)->first()->toArray();
    }

    /**
     * 获取动态
     * @param array|string[] $column
     */
    public function getMoving(int $userid, array $query, array $column = ['*']): array
    {
        $page     = ($query['page'] ?? 1) ?: 1;
        $pageSize = $query['page_size'] ?? 10;
        $where    = [['uid', '=', $userid]];

        $orm   = Waterdo::from('waterdo as w')->join('user as u', 'u.id', 'w.doid')->where($where);
        $count = $orm->count();
        $list  = $orm->select($column)->orderBy('id', 'desc')
            ->offset(($page - 1) * $pageSize)->limit($pageSize)->get()->toArray();
        return ['count' => $count, 'list' => $list];
    }

    /**
     * 减少金额.
     */
    public function decrMoney(int $userid, string $money): int
    {
        return User::query()->where('id', $userid)->decrement('money', $money);
    }

    /**
     * 获取预览图.
     */
    public function getPreview(int $picId): string
    {
        $key = ImgSizeStyle::PREVIEW_IMG_KEY . $picId;

        if (!$this->redis->exists($key)) {
            $url = Picture::query()->where('id', $picId)->value('url');

            if (empty($url)) {
                return '';
            }
            $this->redis->setex($key, $url, 60 * 60 * 24);
        }
        return get_img_path($this->redis->get($key));
    }

    /**
     * 是否关注用户.
     * @param mixed $uid
     * @param mixed $targetId
     */
    public function isGuanzhuUser($uid, $targetId): Model|null
    {
        return Guanzhuuser::query()->where(['uid' => $uid, 'bid' => $targetId])->first();
    }

    /**
     * 判断是否有Vip.
     *
     * @param mixed $uid
     * @param $type
     * @param mixed $array
     */
    public function getUserVip($array): Model|null
    {
        return Uservip::query()->where($array)->first();
    }

    //判断有没有权限
    public function jurisdiction($id)
    {
        $userinfo = User::query()->where(['id' => $id])->first();

        if (empty($userinfo) || $userinfo->vip == 0) {
            return false;
        }
        //权限素材
        $sucai = $this->getUserVip(['uid' => $id, 'type' => 1]);

        if (empty($sucai) || $sucai->time < time()) {
            $sucaiquanxian = 0;
        } else {
            $sucaiquanxian = $sucai->vip;
        }
        //文库
        $wenku = $this->getUserVip(['uid' => $id, 'type' => 2]);

        if (empty($wenku) || $wenku->time < time()) {
            $wenkuquanxian = 0;
        } else {
            $wenkuquanxian = $wenku->vip;
        }
        return ['sucai' => $sucaiquanxian, 'wenku' => $wenkuquanxian];
    }

    /**
     * 增加共享分.
     *
     * @param mixed $uid
     * @param mixed $score
     */
    public function incScore($uid, $score = 1): int
    {
        return User::query()->where(['id' => $uid])->increment('score', $score);
    }

    /**
     * 扣除共享分.
     *
     * @param mixed $uid
     * @param mixed $score
     */
    public function decScore($uid, $score = 1): int
    {
        return User::query()->where(['id' => $uid])->decrement('score', $score);
    }

    /**
     * 增加原创币.
     *
     * @param mixed $uid
     * @param mixed $score
     * @param mixed $dc
     */
    public function incDc($uid, $dc = 1): int
    {
        return User::query()->where(['id' => $uid])->increment('dc', $dc);
    }

    /**
     * 扣除原创币.
     *
     * @param mixed $uid
     * @param mixed $score
     * @param mixed $dc
     */
    public function decDc($uid, $dc = 1): int
    {
        return User::query()->where(['id' => $uid])->decrement('dc', $dc);
    }
    /**
     * 获取禁止展示专辑的用户.
     */
    public function blockAlbumUser(): array
    {
        return User::query()->whereRaw("iszj=2 or isyczj=2")->select(['id','iszj','isyczj'])->get()->toArray();
    }


}
