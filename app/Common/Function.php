<?php

declare(strict_types=1);

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Hyperf\DbConnection\Db;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Redis\RedisFactory;
use Hyperf\Redis\RedisProxy;
use Hyperf\Snowflake\IdGeneratorInterface;
use Hyperf\Utils\ApplicationContext;
use League\Flysystem\Filesystem;
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

if (!function_exists('getCache')) {
    /**
     * 获取redis连接池实例.
     */
    function getCache(string $key): string
    {
        $redis = di()->get(RedisFactory::class)->get('cache');
        return $redis->get($key);
    }
}

if (!function_exists('setCache')) {
    /**
     * 获取redis连接池实例.
     * @param mixed $val
     * @param mixed $ttl
     */
    function setCache(string $key, $val, $ttl = 3600 * 24 * 360): bool
    {
        $redis = di()->get(RedisFactory::class)->get('cache');

        if (!is_string($val)) {
            $val = serialize($val);
        }
        return $redis->setex($key, $val, $ttl);
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
            'filter'   => [],
        ];

        foreach ($query as $k => $v) {
            if (!is_array($v) || count($v) < 2) {
                continue;
            }
            $key     = '';
            $boolArr = [];
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
                case 'in':
                    $key     = 'filter';
                    $boolArr = [['terms' => ["{$k}" => $v[1]]]];
                    break;
                default:
                    continue 2;
            }
            $queryString = $v[1];

            if (empty($queryString)) {
                $queryString = '*';
            }

            if (empty($boolArr)) {
                $boolArr = [['query_string' => ['default_field' => "{$k}", 'query' => "{$queryString}"]]];
            }
            //如果是filter则特殊一点
            $params[$key] = array_merge(
                $params[$key],
                $boolArr
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
            $queryArr = is_json($query, true);
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

            if (isset($params['body']['query']['bool']['filter']) && !empty($params['body']['query']['bool']['filter'])) {
                $queryArr['filter'] = array_merge(
                    $queryArr['filter'],
                    $params['body']['query']['bool']['filter']
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
                            'query' => $query,
                        ],
                    ],
                ];
            }

            echo 'EsSearch:' . json_encode($params);
            return $client->search($params);
        };
    }
}

if (!function_exists('format_es_page_raw_data')) {
    /**
     * es搜索闭包.
     * @param mixed $rawData
     * @throws array
     */
    function format_es_page_raw_data($rawData): array
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

if (!function_exists('is_json')) {
    /**
     * 判断字符串是否为 Json 格式.
     *
     * @param string $data Json 字符串
     * @param bool $assoc 是否返回关联数组。默认返回对象
     *
     * @return array|bool|object 成功返回转换后的对象或数组，失败返回 false
     */
    function is_json($data = '', $assoc = false)
    {
        $data = json_decode($data, $assoc);

        if (($data && is_object($data)) || (is_array($data) && !empty($data))) {
            return $data;
        }
        return false;
    }
}

if (!function_exists('snow_flake')) {
    /**
     * 雪花算法生成唯一id.
     */
    function snow_flake(): int
    {
        return di()->get(IdGeneratorInterface::class)->generate();
    }
}

if (!function_exists('get_img_path')) {
    /**
     * 获取图片地址
     * @param string $path 地址
     * @param string $suffix 后缀
     */
    function get_img_path(string $path, string $suffix = ''): string
    {
        if (!str_contains($path, 'http')) {
            $path = env('PUBLIC_DOMAIN') . $path;
        }

        if (empty($suffix)) {
            return $path;
        }
        return $path . '/' . $suffix;
    }
}

if (!function_exists('get_img_path_private')) {
    /**
     * 获取七牛加密后的图片地址
     *
     * @param string $path 地址
     */
    function get_img_path_private(string $path, int $expires = 3600): string
    {
        $filesystem = di()->get(Filesystem::class);
        //获取私有地址,默认过期一个小时
        return $filesystem->getAdapter()->privateDownloadUrl($path, $expires);
    }
}

if (!function_exists('update_all')) {
    /**
     * 批量更新数据库(更新数据内必要要有表主键)  主键存在则替换 不存在则插入.
     * @param $data
     * @param $table //包含表前缀的数据表名
     */
    function update_all($data, $table): bool
    {
        $keyList = array_keys(reset($data));
        $keyStr  = implode(',', $keyList);
        $sql     = 'replace into ' . $table . "({$keyStr})" . ' values';

        foreach ($data as $item) {
            $sql .= "('" . implode("','", array_values($item)) . "'),";
        }
        $sql = substr($sql, 0, -1);
        return Db::insert($sql);
    }
}

if (!function_exists('exception')) {
    /**
     * 快速抛出异常.
     */
    function exception(string $message, int $code = ErrorCode::ERROR)
    {
        throw new BusinessException($code, $message);
    }
}
