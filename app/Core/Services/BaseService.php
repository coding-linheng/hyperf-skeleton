<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * BaseService.php.
 *
 * 服务基类
 *
 * User：YM
 * Date：2019/11/21
 * Time：下午3:21
 */

namespace App\Core\Services;

use App\Core\Constants\StatusCode;
use Hyperf\Di\Annotation\Inject;
use Psr\Container\ContainerInterface;

/**
 * BaseService
 * 服务基类.
 */
class BaseService
{
    /**
     * @Inject
     * @var ContainerInterface
     */
    protected ContainerInterface $container;

    /**
     * Created by PhpStorm.
     * 可以实现自动注入的业务容器
     * User：YM
     * Date：2020/1/12
     * Time：上午8:18.
     */
    protected array $businessContainerKey = ['auth', 'adminPermission'];

    /**
     * __get
     * 隐式注入服务类
     * User：YM
     * Date：2019/11/21
     * Time：上午9:27.
     * @param $key
     * @return ContainerInterface|void
     */
    public function __get($key)
    {
        if ($key == 'app') {
            return $this->container;
        }

        if (in_array($key, $this->businessContainerKey)) {
            return $this->getBusinessContainerInstance($key);
        }

        if (str_ends_with($key, 'Model')) {
            $key = strstr($key, 'Model', true);
            return $this->getModelInstance($key);
        }

        if (str_ends_with($key, 'Service')) {
            return $this->getServiceInstance($key);
        }
        throw new \RuntimeException("服务/模型{$key}不存在，书写错误！", StatusCode::ERR_SERVER);
    }

    /**
     * getBusinessContainerInstance
     * 获取业务容器实例
     * User：YM
     * Date：2020/1/12
     * Time：上午8:15.
     * @param $key
     * @return mixed
     */
    public function getBusinessContainerInstance($key): mixed
    {
        $key       = ucfirst($key);
        $fileName  = BASE_PATH . "/app/Core/Common/Container/{$key}.php";
        $className = "Core\\Common\\Container\\{$key}";

        if (file_exists($fileName)) {
            return $this->container->get($className);
        }
        throw new \RuntimeException("通用容器{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }

    /**
     * getModelInstance
     * 获取数据模型类实例
     * User：YM
     * Date：2019/11/21
     * Time：上午10:30.
     * @param $key
     * @return mixed
     */
    public function getModelInstance($key): mixed
    {
        $key       = ucfirst($key);
        $fileName  = BASE_PATH . "/app/Models/{$key}.php";
        $className = "App\\Model\\{$key}";

        if (file_exists($fileName)) {
            //model一般不要常驻内存
            //return $this->container->get($className);
            return make($className);
        }
        throw new \RuntimeException("服务/模型{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }

    /**
     * getServiceInstance
     * 获取服务类实例
     * User：YM
     * Date：2019/11/21
     * Time：上午10:30.
     * @param $key
     * @return mixed
     */
    public function getServiceInstance($key): mixed
    {
        $key       = ucfirst($key);
        $fileName  = BASE_PATH . "/app/Core/Services/{$key}.php";
        $className = "Core\\Services\\{$key}";

        if (file_exists($fileName)) {
            return $this->container->get($className);
        }
        throw new \RuntimeException("服务/模型{$key}不存在，文件不存在！", StatusCode::ERR_SERVER);
    }
}