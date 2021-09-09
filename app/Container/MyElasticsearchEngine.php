<?php

declare(strict_types=1);

namespace App\Container;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\Scout\Builder;
use Hyperf\Scout\Engine\ElasticsearchEngine;
use Hyperf\Scout\Engine\Engine;
use Hyperf\Utils\Collection as BaseCollection;

class MyElasticsearchEngine extends Engine
{
    private static ElasticsearchEngine $baseEngine;

    public function __call($name, $arguments)
    {
        if (empty(self::$baseEngine)) {
            $config           = di()->get(ConfigInterface::class);
            $builder          = di()->get(ClientBuilderFactory::class)->create();
            $drive            = $config->get('scout.default', 'elasticsearch');
            $client           = $builder->setHosts($config->get("scout.engine.{$drive}.hosts"))->build();
            $index            = $config->get("scout.engine.{$drive}.index");
            self::$baseEngine = new ElasticsearchEngine($client, $index);
        }
        return self::$baseEngine->{$name}(...$arguments);
    }

    public function search(Builder $builder)
    {
        //todo 自定义引擎搜索方法
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function update(Collection $models): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    public function delete(Collection $models): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }

    public function paginate(Builder $builder, int $perPage, int $page)
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function mapIds($results): BaseCollection
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function map(Builder $builder, $results, Model $model): Collection
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getTotalCount($results): int
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function flush(Model $model): void
    {
        $this->__call(__FUNCTION__, func_get_args());
    }
}
