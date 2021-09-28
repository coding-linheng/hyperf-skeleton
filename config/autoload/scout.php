<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

return [
    'default' => env('SCOUT_ENGINE', 'my_elasticsearch'),
    'chunk' => [
        'searchable' => 500,
        'unsearchable' => 500,
    ],
    'prefix' => env('SCOUT_PREFIX', 'dc10000'),
    'soft_delete' => false,
    'concurrency' => 100,
    'engine' => [
        'my_elasticsearch' => [
            'driver' => App\Driver\Es\EsSearchProvider::class,
            'index' => 'string',
            'hosts' => [
                env('ELASTICSEARCH_HOST', 'http://119.23.59.3:9200'),
            ],
        ],
        'elasticsearch' => [
            'driver' => Hyperf\Scout\Provider\ElasticsearchProvider::class,
            'index' => 'string',
            'hosts' => [
                env('ELASTICSEARCH_HOST', 'http://119.23.59.3:9200'),
            ],
        ],
    ],
];
