<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AbstractController;
use App\Model\Conf;
use Hyperf\Redis\RedisProxy;
use Psr\Http\Message\ResponseInterface;

/*
 * 帮助中心 热点数据
 */

class HelpCenter extends AbstractController
{
    public const HELP_CENTER = 'HELP_CENTER';

    protected ?RedisProxy $redis;

    public function __construct()
    {
        $this->redis = redis('cache');
    }

    public function getHelpList(): ResponseInterface
    {
        $list = json_decode($this->redis->get(self::HELP_CENTER) ?: '', true);

        if (empty($list)) {
            $list = Conf::query()->select(['id', 'title'])->get()->toArray();
            $this->redis->set(self::HELP_CENTER, json_encode($list));
        }
        return $this->success(['count' => count($list), 'list' => $list]);
    }

    public function getHelpDetail(): ResponseInterface
    {
        $id   = $this->request->input('help_id');
        $data = Conf::query()->where('id', $id)->first();

        if (empty($data)) {
            $this->error('资源不存在');
        }
        return $this->success($data);
    }
}
