<?php

declare(strict_types=1);

namespace App\Repositories\V1;

use App\Model\Notice;
use App\Repositories\BaseRepository;
use Hyperf\Database\Model\Collection;

/**
 * 消息处理库.
 */
class MessageRepository extends BaseRepository
{
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
     * 获取最新数量私信消息.
     */
    public function getPrivateMessageLimit(array $where, int $limit): Collection|array
    {
        return Notice::query()->where($where)->orderBy('time')->limit($limit)->get();
    }

    /**
     * 获取所有的私信
     */
    public function getPrivateMessageList(array $where): array
    {
        $orm   = Notice::query()->where($where);
        $count = $orm->count();
        $list  = $orm->orderBy('id', 'desc')->get();
        return ['count' => $count, 'list' => $list->toArray()];
    }
}
