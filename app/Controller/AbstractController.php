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

namespace App\Controller;

use App\Core\Constants\StatusCode;
use App\Core\Container\Response;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\Redis\Redis;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;

abstract class AbstractController
{
    /**
     * @Inject
     *
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     *
     * @var RequestInterface
     */
    protected $request;

    /**
     * @Inject
     *
     * @var Response
     */
    protected $response;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Redis
     */
    protected $redis;

    /**
     * __get
     * 隐式注入仓库类
     * User：YM
     * Date：2019/11/21
     * Time：上午9:27.
     * @param $key
     * @return \Psr\Container\ContainerInterface|void
     */
    public function __get($key)
    {
        if ($key == 'app') {
            return $this->container;
        }
        $suffix = strstr($key, 'Repo');

        if ($suffix && ($suffix == 'Repo' || $suffix == 'Repository')) {
            $repoName = $suffix == 'Repo' ? $key . 'sitory' : $key;
            return $this->getRepositoriesInstance($repoName);
        }
        throw new \RuntimeException("仓库{$key}不存在，书写错误！", StatusCode::ERR_SERVER);
    }

    /**
     * getRepositoriesInstance
     * 获取仓库类实例
     * User：YM
     * Date：2019/11/21
     * Time：上午10:30.
     * @param $key
     * @return mixed
     */
    public function getRepositoriesInstance($key)
    {
        $key    = ucfirst($key);
        $module = $this->getModuleName();

        if (!empty($module)) {
            $module = "{$module}";
        } else {
            $module = '';
        }

        if ($module) {
            $filename  = BASE_PATH . "/app/Core/Repositories/{$module}/{$key}.php";
            $className = "Core\\Repositories\\{$module}\\{$key}";
        } else {
            $filename  = BASE_PATH . "/app/Core/Repositories/{$key}.php";
            $className = "Core\\Repositories\\{$key}";
        }
        echo $filename;
        echo $className;

        if (file_exists($filename)) {
            return $this->container->get($className);
        }
        throw new \RuntimeException("仓库{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }

    /**
     * success
     * 成功返回请求结果
     * User：YM
     * Date：2019/11/20
     * Time：下午3:56.
     * @param array $data
     * @param null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function success($data = [], string $msg = null)
    {
        return $this->response->success($data, $msg);
    }

    /**
     * error
     * 业务相关错误结果返回
     * User：YM
     * Date：2019/11/20
     * Time：下午3:56.
     * @param int $code
     * @param null $msg
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function error($code = StatusCode::ERR_EXCEPTION, string $msg = '')
    {
        return $this->response->error($code, $msg);
    }

    /**
     * getModuleName
     * 获取所属模块
     * User：YM
     * Date：2019/11/21
     * Time：上午9:32.
     * @return string
     */
    private function getModuleName()
    {
        $className = get_called_class();
        $name      = substr($className, 15);
        $space     = explode('\\', $name);

        if (count($space) > 1) {
            return $space[0];
        }
        return '';
    }
}
