<?php

declare(strict_types=1);

namespace App\Services;

use App\Constants\ErrorCode;
use App\Constants\UserCenterStatus;
use App\Exception\BusinessException;
use App\Model\Geshi;
use App\Model\Geshirelation;
use App\Model\Img;
use App\Model\Mulu;
use App\Model\Mulurelation;
use App\Model\Noticelook;
use App\Model\Tixian;
use App\Model\User;
use App\Model\Userdata;
use App\Model\Wenku;
use App\Repositories\V1\AlbumRepository;
use App\Repositories\V1\MessageRepository;
use App\Repositories\V1\SucaiRepository;
use App\Repositories\V1\UserRepository;
use App\Repositories\V1\WaterDoRepository;
use App\Repositories\V1\WenkuRepository;
use Exception;
use Hyperf\Database\Model\Model;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Inject;

/**
 * UserService
 * 用户中心相关逻辑.
 */
class UserService extends BaseService
{
    #[Inject]
    protected UserRepository $userRepository;

    #[Inject]
    protected AlbumRepository $albumRepository;

    #[Inject]
    protected WenkuRepository $wenkuRepository;

    #[Inject]
    protected SucaiRepository $sucaiRepository;

    #[Inject]
    protected MessageRepository $messageRepository;

    #[Inject]
    protected WaterDoRepository $waterDoRepository;

    public function __call($name, $arguments)
    {
        return $this->userRepository->{$name}(...$arguments);
    }

    public function login(string $username, string $password): User
    {
        /** @var User $user */
        $user = User::query()->where('username', $username)->first();

        if (empty($user)) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '账号不存在');
        }

        if ($user['jinzhi'] == 2) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '您的账号因涉嫌违规操作，被系统临时冻结，如有问题，请联系客服');
        }

        if (md5($password) != $user['password']) {
            throw new BusinessException(ErrorCode::LOGIN_FAIL, '密码错误');
        }
        $user->contentId = $user['id'];
        return $user;
    }

    /**
     * 获取动态
     * @param array|string[] $column
     */
    public function getMoving(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->waterDoRepository->getMoving($userid, $query, $column);

        if (empty($data['list'])) {
            return $data;
        }

        foreach ($data['list'] as &$v) {
            $detail    = match ($v['type']) {
                1, 2 => $this->albumRepository->getAlbumDetail(['a.id' => $v['cid']], ['a.name', 'l.path']),
                4, 6, 10 => $this->albumRepository->getAlbumListDetail(['id' => $v['cid']], ['name', 'path']),
                5, 9 => $this->wenkuRepository->getLibraryDetail(['id' => $v['cid']], ['name', 'pdfimg as path']),
                7, 8 => $this->sucaiRepository->getSucaiImgInfo(['id' => $v['cid']], ['title as name', 'path']),
                default => null,
            };
            $v['name'] = $detail ? $detail->toArray()['name'] : '';
            $v['path'] = $detail ? get_img_path_private($detail->toArray()['path']) : '';
        }
        unset($v);
        return $data;
    }

    /**
     * 获取私信
     * @param array|string[] $column
     */
    public function getPrivateMessage(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->messageRepository->getPrivateMessage($userid, $query, $column);

        if (empty($data['list'])) {
            return $data;
        }
        return $this->checkRead($userid, $data);
    }

    /**
     * 获取系统公告.
     * @param array|string[] $column
     */
    public function getSystemMessage(int $userid, array $query, array $column = ['*']): array
    {
        $data = $this->messageRepository->getSystemMessage($query, $column);

        if (empty($data['list'])) {
            return $data;
        }
        return $this->checkRead($userid, $data);
    }

    /**
     * 获取消息内容.
     */
    public function getMessageDetail(int $noticeId): array
    {
        return $this->messageRepository->getMessageDetail($noticeId);
    }

    /**
     * 用户收益.
     */
    public function getUserIncome(int $userid): array
    {
        return [
            'day'        => $this->userRepository->todayIncome($userid) ?? 0,
            'yesterday'  => $this->userRepository->yesterdayIncome($userid) ?? 0,
            'this_week'  => $this->userRepository->thisWeekIncome($userid) ?? 0,
            'last_week'  => $this->userRepository->lastWeekIncome($userid) ?? 0,
            'this_month' => $this->userRepository->thisMonthIncome($userid) ?? 0,
            'last_month' => $this->userRepository->lastMonthIncome($userid) ?? 0,
        ];
    }

    public function getUserData(int $userid): Userdata
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getUser(int $userid): User
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getUserMerge(int $userid, $column = ['*']): Model
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getMoneyLog(int $userid, array $query, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getScoreLog(int $userid, array $query, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function getCashLog(int $userid, int $page, int $pageSize, array $column = ['*']): array
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    public function decrMoney(int $userid, string $money): int
    {
        return $this->__call(__FUNCTION__, func_get_args());
    }

    /**
     * 提现.
     */
    public function cash(int $userid, string $money): bool
    {
        if (!is_numeric($money)) {
            throw new BusinessException(ErrorCode::ERROR, '请输入正确的金额');
        }

        if ($money < 100) {
            throw new BusinessException(ErrorCode::ERROR, '满100才能提现');
        }
        Db::beginTransaction();
        try {
            $userdata = $this->userRepository->getUserData($userid);

            if (empty($userdata['zhi'])) {
                throw new Exception('支付宝信息未填写');
            }

            if ($userdata['status'] != UserCenterStatus::USER_CERT_IS_PASS) {
                throw new Exception('用户认证未通过');
            }

            if (Tixian::query()->where(['uid' => $userid, 'status' => UserCenterStatus::USER_CASH_NO_VERIFY])->exists()) {
                throw new Exception('您有正在审核的提现申请,请勿重复提交');
            }
            $user = $this->userRepository->getUser($userid);

            if ($user->money < $money) {
                throw new Exception('可提现金额不足');
            }
            //减少金额
            $this->decrMoney($userid, $money);
            //增加提现记录
            $cashLog = [
                'uid'    => $userid,
                'money'  => $money,
                'status' => UserCenterStatus::USER_CASH_NO_VERIFY,
                'name'   => $userdata->name,
                'zhi'    => $userdata->zhi,
                'time'   => time(),
            ];
            Tixian::create($cashLog);
            Db::commit();
            return true;
        } catch (Exception $e) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, $e->getMessage());
        }
    }

    /**
     * 上传作品
     */
    public function uploadWork(int $userid, array $fileData, int $type): Wenku|Model|Img
    {
        $save = array_merge(['uid' => $userid, 'unnum' => snow_flake(), 'time' => time()], $fileData);
        return match ($type) {
            1 => Img::create($save),
            2 => Wenku::create($save),
        };
    }

    /**
     * 作品管理.
     */
    public function worksManageForMaterial(int $userid, array $query, array $column = ['*']): array
    {
        $countArr = $this->sucaiRepository->getMaterialStatistics(['uid' => $userid, 'del' => 0]);
        $where    = ['uid' => $userid, 'status' => $query['status'], 'del' => 0];
        $list     = $this->sucaiRepository->getMaterialList($where, $query, $column);
        //预览图处理  共享分处理
        foreach ($list['list'] as $k => $v) {
            if ($v['leixing'] == 1) {
                $list['list'][$k]['star']  = (int)$v['price'];
                $list['list'][$k]['price'] = $this->getscore((int)$v['price'])['score'];
            }
            $preview = $this->userRepository->getPictureJson($v['img']);

            $list['list'][$k]['preview'] = $preview ?? '';
            $list['list'][$k]['time']    = date('Y-m-d H:i:s', (int)$v['time']);
            $list['list'][$k]['size']    = sprintf('%.2f', $list['list'][$k]['size'] / 1024 / 1024);
        }
        return array_merge($list, ['count_arr' => $countArr]);
    }

    public function worksManageForLibrary(int $userid, array $query, array $column = ['*']): array
    {
        $countArr = $this->wenkuRepository->getLibraryStatistics(['uid' => $userid, 'del' => 0]);
        $where    = ['uid' => $userid, 'status' => $query['status'], 'del' => 0];
        $list     = $this->wenkuRepository->getLibraryList($where, $query, $column);
        //预览图处理  共享分处理
        foreach ($list['list'] as $k => $v) {
            $preview = $this->userRepository->getPictureJson($v['img']);

            $list['list'][$k]['preview'] = $preview ?? '';
            $list['list'][$k]['size']    = sprintf('%.2f', $list['list'][$k]['size'] / 1024 / 1024);
        }
        return array_merge($list, ['count_arr' => $countArr]);
    }

    /**
     * 填写信息-素材.
     */
    public function writeInformationForMaterial(array $params): bool
    {
        $params['status'] = UserCenterStatus::WORK_MANAGE_REVIEW;
        $params['text']   = '';
        $params['img']    = json_encode(explode(',', $params['img']));
        Db::beginTransaction();
        try {
            $material = $this->sucaiRepository->getSucaiImgInfo(['id' => $params['material_id']]);

            if (!in_array($material['status'], [UserCenterStatus::WORK_MANAGE_PENDING, UserCenterStatus::WORK_MANAGE_REVISION])) {
                throw new Exception('文库已被处理,无需再次填写');
            }
            //记录关联信息
            Mulurelation::query()->updateOrCreate(['mid' => $params['mulu_id'], 'iid' => $params['material_id']]);
            Geshirelation::query()->updateOrCreate(['mid' => $params['geshi_id'], 'iid' => $params['material_id']]);
            Db::commit();
            return $material->fill($params)->save();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '填写失败' . $exception->getMessage());
        }
    }

    /**
     * 获取素材分类.
     */
    public function getMaterialCategory(): array
    {
        return Mulu::query()->get(['id', 'name'])->toArray();
//        $category = Mulu::query()->pluck('name', 'id')->toArray();
//
//        if (empty($category)) {
//            return [];
//        }
//        $children = Fenlei::query()->whereIn('mid', array_keys($category))->select(['id', 'name', 'mid'])->get()->toArray();
//        $data     = [];
//
//        foreach ($children as $v) {
//            $data[$v['mid']]['id']         = $v['mid'];
//            $data[$v['mid']]['name']       = $category[$v['mid']];
//            $data[$v['mid']]['children'][] = $v;
//        }
//        return array_values($data);
    }

    /**
     * 获取素材格式.
     */
    public function getMaterialFormat(): array
    {
        return Geshi::query()->select(['id', 'name'])->get()->toArray();
    }

    /**
     * 删除素材.
     */
    public function deleteForMaterial(array $ids): mixed
    {
        return $this->sucaiRepository->deleteImg($ids);
    }

    /**
     * 获取素材详情.
     */
    public function getDetailForMaterial(int $id, array $column): array
    {
        $where              = ['id' => $id];
        $info               = $this->sucaiRepository->getSucaiImgInfo($where, $column);
        $info['size']       = $info['size'] / 1024 / 1024;
        $info['mulu_name']  = Mulu::query()->where('id', $info['mulu_id'])->value('name')   ?? '';
        $info['geshi_name'] = Geshi::query()->where('id', $info['geshi_id'])->value('name') ?? '';
        return array_merge($info->toArray(), ['preview' => $this->userRepository->getPictureJson($info['img'])]);
    }

    /**
     * 获取文库详情.
     */
    public function getDetailForLibrary(int $id, array $column): array
    {
        $where        = ['id' => $id];
        $info         = $this->wenkuRepository->getLibraryDetail($where, $column);
        $info['size'] = $info['size'] / 1024 / 1024;
        return array_merge($info->toArray(), ['preview' => $this->userRepository->getPictureJson($info['img'])]);
    }

    public function writeInformationForLibrary(array $params): bool
    {
        $params['status'] = UserCenterStatus::WORK_MANAGE_REVIEW;
        $params['text']   = '';
        $params['img']    = json_encode(explode(',', $params['img']));
        Db::beginTransaction();
        try {
            $material = $this->wenkuRepository->getLibraryDetail(['id' => $params['library_id']]);

            if (!in_array($material['status'], [UserCenterStatus::WORK_MANAGE_PENDING, UserCenterStatus::WORK_MANAGE_REVISION])) {
                throw new Exception('文库已被处理,无需再次填写');
            }
            Db::commit();
            return $material->fill($params)->save();
        } catch (\Exception $exception) {
            Db::rollBack();
            throw new BusinessException(ErrorCode::ERROR, '填写失败' . $exception->getMessage());
        }
    }

    /**
     * 删除文库.
     */
    public function deleteForLibrary(array $ids): int
    {
        return $this->wenkuRepository->deleteLibrary($ids);
    }

    /**
     * 消息盒.
     */
    public function getMessageBox(int $userid): array
    {
        $where  = ['pid' => $userid];
        $list   = $this->messageRepository->getPrivateMessageList($where);
        $count  = Noticelook::query()->where(['uid' => $userid])->whereIn('nid', array_column($list['list'], 'id'))->count();
        $where  = ['uid' => $userid];
        $field  = ['w.id', 'w.cid', 'w.time', 'w.type', 'w.uid', 'u.nickname', 'u.imghead'];
        $moving = $this->waterDoRepository->getMovingLimit($where, 4, $field);

        foreach ($moving as &$v) {
            $detail    = match ($v['type']) {
                1, 2 => $this->albumRepository->getAlbumDetail(['a.id' => $v['cid']], ['a.name', 'l.path']),
                4, 6, 10 => $this->albumRepository->getAlbumListDetail(['id' => $v['cid']], ['name', 'path']),
                5, 9 => $this->wenkuRepository->getLibraryDetail(['id' => $v['cid']], ['name', 'pdfimg as path']),
                7, 8 => $this->sucaiRepository->getSucaiImgInfo(['id' => $v['cid']], ['title as name', 'path']),
                default => null,
            };
            $v['name'] = $detail ? $detail->toArray()['name'] : '';
            $v['path'] = $detail ? get_img_path_private($detail->toArray()['path']) : '';
        }
        unset($v);
        return ['no_read_count' => $list['count'] - $count, 'message_list' => array_slice($list['list'], 0, 5), 'moving_list' => $moving];
        //获取五条最新动态
    }

    /**
     * 检查消息是否已读.
     */
    private function checkRead(int $userid, array $data): array
    {
        $ids = array_column($data['list'], 'id');
        $nid = Noticelook::query()->where('uid', $userid)->whereIn('nid', $ids)->pluck('nid')->toArray();

        $data['no_read_count'] = 0;

        foreach ($data['list'] as &$v) {
            $v['read'] = 1;

            if (!in_array($v['id'], $nid)) {
                $v['read'] = 0;
                ++$data['no_read_count'];
            }
        }
        unset($v);
        return $data;
    }
}
