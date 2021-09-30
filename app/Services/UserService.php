<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Noticelook;
use App\Model\User;
use App\Model\Userdata;
use App\Repositories\V1\AlbumRepository;
use App\Repositories\V1\UserRepository;
use Hyperf\Database\Model\Model;
use Hyperf\Di\Annotation\Inject;

/**
 * UserService
 * 用户相关逻辑.
 */
class UserService extends BaseService
{
    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected AlbumRepository $albumRepository;

    public function __call($name, $arguments)
    {
        return $this->userRepository->{$name}(...$arguments);
    }

    public function login(string $username, string $password): User
    {
        /** @var User $user */
        $user = User::query()->where('username', $username)->first();

        if (empty($user)) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '账号不存在');
        }

        if ($user['jinzhi'] == 2) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '您的账号因涉嫌违规操作，被系统临时冻结，如有问题，请联系客服');
        }

        if (md5($password) != $user['password']) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '密码错误');
        }
        $user->contentId = $user['id'];
        return $user;
    }

    /**
     * 获取动态
     * @param array|string[] $column
     */
    public function getMoving(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->userRepository->getMoving($userid, $query, $column);

        if (empty($data['list'])) {
            return $data;
        }

        foreach ($data['list'] as &$v) {
            $detail    = match ($v['type']) {
                1, 2 => $this->albumRepository->getAlbumDetail(['a.id' => $v['cid']], ['a.name', 'l.path']),
                4, 6, 10 => $this->albumRepository->getAlbumListDetail(['id' => $v['cid']], ['name', 'path']),
                5, 9 => $this->albumRepository->getLibraryDetail(['id' => $v['cid']], ['name', 'pdfimg as path']),
                7, 8 => $this->albumRepository->getMaterialDetail(['id' => $v['cid']], ['title as name', 'path']),
                default => null,
            };
            $v['name'] = $detail ? $detail->toArray()['name'] : '';
            $v['path'] = $detail ? $detail->toArray()['path'] : '';
        }
        unset($v);
        return $data;
    }

    /**
     * 获取私信
     * @param array|string[] $column
     */
    public function getPrivateMessage(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->userRepository->getPrivateMessage($userid, $query, $column);

        if (empty($data['list'])) {
            return $data;
        }
        return $this->checkRead($userid, $data);
    }

    /**
     * 获取系统公告.
     * @param array|string[] $column
     */
    public function getSystemMessage(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->userRepository->getSystemMessage($query, $column);

        if (empty($data['list'])) {
            return $data;
        }
        return $this->checkRead($userid, $data);
    }

    /**
     * 获取消息内容.
     */
    public function getMessageDetail(int $noticeId): array
    {
        return $this->userRepository->getMessageDetail($noticeId);
    }

    /**
     * 用户收益.
     */
    public function getUserIncome(int $userid): array
    {
        return [
            'day'        => $this->userRepository->todayIncome($userid) ?? 0,
            'yesterday'  => $this->userRepository->yesterdayIncome($userid) ?? 0,
            'this_week'  => $this->userRepository->thisWeekIncome($userid) ?? 0,
            'last_week'  => $this->userRepository->lastWeekIncome($userid) ?? 0,
            'this_month' => $this->userRepository->thisMonthIncome($userid) ?? 0,
            'last_month' => $this->userRepository->lastMonthIncome($userid) ?? 0,
        ];
    }

    public function getUserData(int $userid): Userdata
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getUser(int $userid): User
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getUserMerge(int $userid, $column = ['*']): Model
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getMoneyLog(int $userid, array $query, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getScoreLog(int $userid, array $query, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getCashLog(int $userid, int $page, int $pageSize, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * 检查消息是否已读.
     */
    private function checkRead(int $userid, array $data): array
    {
        $ids = array_column($data['list'], 'id');
        $nid = Noticelook::query()->where('uid', $userid)->whereIn('nid', $ids)->pluck('nid')->toArray();

        foreach ($data['list'] as &$v) {
            $v['read'] = 0;

            if (in_array($v['id'], $nid)) {
                $v['read'] = 1;
            }
        }
        unset($v);
        return $data;
    }
}
