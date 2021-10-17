<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Exception;
use Hyperf\HttpMessage\Upload\UploadedFile;
use League\Flysystem\Filesystem;

/*
 * 助手类
 */

class Utils
{
    /**
     * 上传文件.
     * @param UploadedFile $file 文件
     * @param string[] $suffixArr 限制上传类型
     */
    public static function upload(UploadedFile $file, array $suffixArr = ['jpg', 'rar', 'zip']): array
    {
        try {
            if (empty($file) || !$file->isValid()) {
                throw new Exception('上传失败');
            }

            if (!in_array($file->getExtension(), $suffixArr)) {
                throw new Exception('上传失败:非法上传格式');
            }

            $size = sprintf('%.2f', $file->getSize() / 1024 / 1024);
            $ext  = $file->getExtension();

            $maxSize = match ($ext) {
                'jpg' => 5,
                'rar', 'zip' => 1024
            };

            if ($size > $maxSize) {
                throw new Exception('上传失败:文件过大');
            }
            //保证数据唯一不重复上传
            $stream     = fopen($file->getRealPath(), 'r+');
            $suffix     = $file->getextension();
            $path       = 'public/uploads/' . md5_file($file->getRealPath()) . '.' . $suffix;
            $filesystem = make(Filesystem::class);
            $data       = [
                'path'      => $path,
                'size'      => $file->getSize(),
                'suffix'    => $suffix,
                'name'      => md5_file($file->getRealPath()) . '.' . $suffix,
                'image_url' => env('PUBLIC_DOMAIN') . '/' . $path
            ];

            if ($filesystem->has($path)) {
                //空间已存在则直接返回
                return $data;
            }
            $filesystem->writeStream($path, $stream);
            //获取私有地址 $filesystem->getAdapter()->privateDownloadUrl("");
            fclose($stream);
            return $data;
        } catch (Exception $e) {
            throw new BusinessException(ErrorCode::UPLOAD_FAIL, $e->getMessage());
        }
    }
}
