<?php

declare(strict_types=1);

namespace App\Controller\V1;

use App\Controller\AbstractController;
use App\Model\Conf;
use App\Model\Question;
use App\Model\QuestionType;
use Hyperf\Redis\RedisProxy;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
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
        $id     = $this->request->input('help_id');
        $column = ['title', 'content'];
        $data   = Conf::query()->where('id', $id)->select($column)->first();

        if (empty($data)) {
            $this->error('资源不存在');
        }
        return $this->success($data);
    }

    /**
     * 获取常见问题列表.
     */
    public function getQuestionList(): ResponseInterface
    {
        $type   = QuestionType::query()->pluck('name', 'id');
        $data   = [];
        $column = ['title', 'content'];

        foreach ($type as $id => $name) {
            $data[$id]['id']   = $id;
            $data[$id]['name'] = $name;
            $data[$id]['list'] = Question::query()->where('type', $id)->select($column)->limit(3)->get();
        }

        return $this->success(array_values($data));
    }

    /*
     * 获取更多问题
     */
    public function getMoreQuestion(): ResponseInterface
    {
        $type = $this->request->input('type');
        $list = Question::query()->where('type', $type)->get();
        return $this->success($list);
    }

    public function FeedbackQuestion(ValidatorFactoryInterface $validatorFactory): ResponseInterface
    {
        $validator = $validatorFactory->make($this->request->all(), ['content' => 'required']);

        if ($validator->fails()) {
            $this->error($validator->errors()->first());
        }
        Question::create($this->request->all());
        return $this->success();
    }
}
