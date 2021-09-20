<?php

declare(strict_types=1);

namespace App\Controller;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use League\Flysystem\Filesystem;
use Psr\Http\Message\ResponseInterface;

/*
 * 助手类
 */
class Utils extends AbstractController
{
    public function upload(Filesystem $filesystem): ResponseInterface
    {
        try {
            $file = $this->request->file('upload');

            if (empty($file) || !$file->isValid()) {
                throw new \Exception('上传失败');
            }
            $stream = fopen($file->getRealPath(), 'r+');
            $path   = 'public/uploads/' . $file->getClientFilename();

            if ($filesystem->has($path)) {
                //空间已存在则直接返回
                return $this->response->success(env('PUBLIC_DOMAIN') . '/' . $path);
            }
            $filesystem->writeStream($path, $stream);
            //获取私有地址 $filesystem->getAdapter()->privateDownloadUrl("");
            fclose($stream);
            return $this->response->success(env('PUBLIC_DOMAIN') . '/' . $path);
        } catch (\Exception $e) {
            throw new BusinessException(ErrorCode::UPLOAD_FAIL, $e->getMessage());
        }
    }
}
