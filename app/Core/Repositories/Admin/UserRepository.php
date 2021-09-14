<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 *​
 * UserRepository.php
 *
 * 文件描述
 *
 * User：Willion
 * Date：2021/9/15
 */


namespace App\Core\Repositories\Admin;


use Core\Common\Container\Auth;
use Core\Repositories\BaseRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * UserRepository
 * 类的介绍
 * @package Core\Repositories\Admin
 * User：Willion
 * Date：2021/9/15
 *
 * @property App\Core\Services\UserService $userService
 *
 */
class UserRepository extends BaseRepository
{


    /**
     * getInfo
     * 根据id获取信息
     * User：YM
     * Date：2020/2/5
     * Time：下午4:28
     * @param $id
     * @return \Hyperf\Database\Model\Model|null
     */
    public function getInfo($id)
    {
        $info               = $this->userService->getInfo($id);
        $info['user_roles'] = $this->rolesService->getUserRoles($id);
        return $info;
    }



}