<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */


namespace App\Core\Services;

use App\Core\Exception\BusinessException;

/**
 * UserService
 * 类的介绍
 * @package Core\Services
 *
 * @property \APP\Model\User $userModel
 */
class UserService extends BaseService
{

    /**
     * getInfo
     * 获取用户数据
     * User：YM
     * Date：2020/1/8
     * Time：下午7:52
     * @param string|array $id 可以传入数组
     * @param bool $type 是否使用缓存
     * @return \Hyperf\Database\Model\Model|null|array
     *
     */
    public function getInfo($id, $type = true)
    {
        $res = $this->userModel->getInfo($id, $type);
        if (count($res) == count($res, 1)) {
            unset($res['password']);
            unset($res['session_id']);
            unset($res['deleted_at']);
        } else {
            foreach ($res as &$v) {
                unset($v['password']);
                unset($v['session_id']);
                unset($v['deleted_at']);
            }
            unset($v);
        }

        return $res;
    }



}