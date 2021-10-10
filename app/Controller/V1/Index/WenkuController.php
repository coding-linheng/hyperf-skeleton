<?php

declare(strict_types=1);

namespace App\Controller\V1\Index;

use App\Controller\AbstractController;
use App\Request\Sucai;
use App\Services\SucaiService;
use Hyperf\Di\Annotation\Inject;
use Psr\Http\Message\ResponseInterface;

/*
 * 素材相关操作
 */

class WenkuController extends AbstractController
{
    #[Inject]
    protected SucaiService $sucaiService;



}
