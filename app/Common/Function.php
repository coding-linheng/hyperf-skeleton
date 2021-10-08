<?php

declare(strict_types=1);

use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\RedisProxy;
use Hyperf\Utils\ApplicationContext;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

if (!function_exists('user')) {
    /**
     * jwt用户信息.
     */
    function user()
    {
        $request = ApplicationContext::getContainer()->get(ServerRequestInterface::class);
        return $request->getAttribute('user', []);
    }
}

if (!function_exists('di')) {
    /**
     * 获取di容器.
     */
    function di(): ContainerInterface
    {
        return ApplicationContext::getContainer();
    }
}

if (!function_exists('redis')) {
    /**
     * 获取redis连接池实例.
     */
    function redis(string $name = 'default'): RedisProxy
    {
        return di()->get(RedisFactory::class)->get($name);
    }
}

if (!function_exists('logger')) {
    /**
     * 获取指定日志实例.
     */
    function logger(string $name = 'hyperf', string $group = 'default'): LoggerInterface
    {
        return di()->get(LoggerFactory::class)->get($name, $group);
    }
}

if (!function_exists('request')) {
    /**
     * 获取请求实例.
     */
    function request(): RequestInterface
    {
        return di()->get(RequestInterface::class);
    }
}

if (!function_exists('get_client_ip')) {
    /**
     * 获取客户端ip.
     * @throws TypeError
     */
    function get_client_ip(): string
    {
        return request()->getHeaderLine('x-real-ip') ?: request()->server('remote_addr');
    }
}

if (!function_exists('es_query_format')) {
    /**
     * es搜索闭包.
     * @param mixed $query
     *                     {"query":{"bool":{"must":[],"must_not":[],"should":[{"query_string":{"default_field":"title","query":"春节吃到嗨"}},{"query_string":{"default_field":"label","query":""}}]}},"from":0,"size":250,"sort":[],"aggs":{}}
     */
    function es_query_format($query)
    {
        if (!is_array($query)) {
            return $query;
        }
        $params = [
            'must'     => [],
            'must_not' => [],
            'should'   => [],
        ];

        foreach ($query as $k => $v) {
            if (!is_array($v) || count($v) < 2) {
                continue;
            }
            $key = '';
            switch ($v[0]) {
                case '||':
                case 'or':
                case '|':
                    $key = 'should';
                    break;
                case 'and':
                case '&&':
                case '&':
                    $key = 'must';
                    break;
                case 'not':
                case '!':
                    $key = 'must_not';
                    break;
                default:
                    continue 2;
            }
            $queryString  = $v[1];
            $params[$key] = array_merge(
                $params[$key],
                [['query_string' => ['default_field' => "{$k}", 'query' => "{$queryString}"]]]
            );
        }
        return json_encode($params);
    }
}

if (!function_exists('es_callback')) {
    /**
     * es搜索闭包.
     * @param mixed $query
     * @param mixed $isFormat
     * @throws TypeError
     */
    function es_callback($query, $isFormat = true): Closure
    {
        return function ($client, $builder, $params) use ($query, $isFormat) {
            //判断query是否是一个json字符串，如果是则json化后并入bool数组内，如果不是则当成字符串操作
            if ($isFormat) {
                $query = es_query_format($query);
            }
            $queryArr = isJson($query, true);
            //如果存在must则优先合并，再覆盖
            if (isset($params['body']['query']['bool']['must']) && !empty($params['body']['query']['bool']['must'])) {
                $queryArr['must'] = array_merge(
                    $queryArr['must'],
                    $params['body']['query']['bool']['must']
                );
            }

            if (isset($params['body']['query']['bool']['should']) && !empty($params['body']['query']['bool']['should'])) {
                $queryArr['should'] = array_merge(
                    $queryArr['should'],
                    $params['body']['query']['bool']['should']
                );
            }

            if (isset($params['body']['query']['bool']['must_not']) && !empty($params['body']['query']['bool']['must_not'])) {
                $queryArr['must_not'] = array_merge(
                    $queryArr['must_not'],
                    $params['body']['query']['bool']['must_not']
                );
            }

            //合并覆盖参数
            if (!empty($queryArr)) {
                $params['body']['query']['bool'] = array_merge(
                    $params['body']['query']['bool'],
                    $queryArr
                );
            } else {
                $params['body']['query']['bool']['should'] = [
                    [
                        'query_string' => [
                            'query'         => $query,
                        ],
                    ],
                ];
            }

            echo 'EsSearch:' . json_encode($params);
            return $client->search($params);
        };
    }
}

if (!function_exists('formatEsPageRawData')) {
    /**
     * es搜索闭包.
     * @param mixed $rawData
     * @throws array
     */
    function formatEsPageRawData($rawData): array
    {
        $tmp = [];

        if (isset($rawData['data']['hits']['hits']) && count($rawData['data']['hits']['hits']) > 0) {
            $hitsDataArr = $rawData['data']['hits']['hits'];

            foreach ($hitsDataArr as $value) {
                $tmp[] = $value['_source'] ?? [];
            }
            $rawData['data'] = $tmp;
        }
        return $rawData;
    }
}

if (!function_exists('isJson')) {
    /**
     * 判断字符串是否为 Json 格式.
     *
     * @param string $data Json 字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     *
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    function isJson($data = '', $assoc = false)
    {
        $data = json_decode($data, $assoc);

        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return false;
    }
}
