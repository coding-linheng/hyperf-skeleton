<?php

declare(strict_types=1);
/**
 * Created by PhpStorm.
 */

namespace App\Services;

use App\Model\User;
use App\Repositories\V1\WenkuRepository;
use Hyperf\Di\Annotation\Inject;

/**
 * WenkuService.
 *
 * @property User $userModel
 */
class WenkuService extends BaseService
{
    #[Inject]
    protected WenkuRepository $wenkuRepository;
}
