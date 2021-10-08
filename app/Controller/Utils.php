<?php

declare(strict_types=1);

namespace App\Controller;

use Psr\Http\Message\ResponseInterface;

/*
 * 助手类
 */

class Utils extends AbstractController
{
    /**
     * 上传接口.
     */
    public function upload(): ResponseInterface
    {
        $file = $this->request->file('upload');
        $data = \App\Common\Utils::upload($file);
        return $this->success($data);
    }
}
