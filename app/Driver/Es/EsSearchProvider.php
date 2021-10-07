<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Driver\Es;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Elasticsearch\ClientBuilderFactory;
use Hyperf\Scout\Engine\Engine;
use Hyperf\Scout\Provider\ProviderInterface;

class EsSearchProvider implements ProviderInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function make(string $name): Engine
    {
        $config  = $this->container->get(ConfigInterface::class);
        $builder = $this->container->get(ClientBuilderFactory::class)->create();
        $client  = $builder->setHosts($config->get("scout.engine.{$name}.hosts"))->build();
        $index   = $config->get("scout.engine.{$name}.index");
        return new EsSearchEngine($client, $index);
    }
}