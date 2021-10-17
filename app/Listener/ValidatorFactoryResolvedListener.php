<?php

declare(strict_types=1);

namespace App\Listener;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use App\Model\Keyword;
use App\Model\KeywordsType;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;
use Hyperf\Validation\Event\ValidatorFactoryResolved;

/**
 * @Listener
 */
class ValidatorFactoryResolvedListener implements ListenerInterface
{
    public function listen(): array
    {
        return [
            ValidatorFactoryResolved::class,
        ];
    }

    public function process(object $event)
    {
        /** @var ValidatorFactoryInterface $validatorFactory */
        $validatorFactory = $event->validatorFactory;
        // 注册了关键词验证器
        $validatorFactory->extend('key_words', function ($attribute, $value, $parameters, $validator) {
            $keys = explode(' ', $value);
            $keys = array_filter($keys);

            if (count($keys) < 10 || count($keys) > 20) {
                return false;
            }
            $mustArr = Keyword::query()->whereIn('type', function ($query) {
                $query->from('keywords_type')->where('must', 1)->select('id')->get();
            })->pluck('name')->toArray();

            $intersect = array_intersect(array_unique($mustArr), array_unique($keys));

            if (KeywordsType::query()->where('must', 1)->count() > count($intersect)) {
                throw new BusinessException(ErrorCode::VALIDATE_FAIL, '关键词必选项不足');
            }
            return true;
        });
        // 当创建一个自定义验证规则时，你可能有时候需要为错误信息定义自定义占位符这里扩展了 :foo 占位符
        $validatorFactory->replacer('key_words', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':key_words', $attribute, $message);
        });
    }
}
