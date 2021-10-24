<?php

declare(strict_types=1);

namespace App\Common;

use App\Constants\ErrorCode;
use App\Exception\BusinessException;
use Exception;
use Hyperf\HttpMessage\Upload\UploadedFile;
use Imagick;
use ImagickPixel;
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
                'image_url' => env('PUBLIC_DOMAIN') . '/' . $path,
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

    /**
     * 将pdf文件转化为多张png图片.
     *
     * @param string $pdf pdf所在路径 （/public/pdf/abc.pdf pdf所在的绝对路径）
     * @param string $path 新生成图片所在路径 (/public/pdf/)
     *
     * @throws Exception
     */
    public static function pdfToMultiPng(string $pdf, string $path): bool|array
    {
        if (!extension_loaded('imagick')) {
            return false;
        }

        if (!file_exists($pdf)) {
            return false;
        }
        $im = new Imagick();
        $im->setResolution(120, 120); //设置分辨率 值越大分辨率越高
        $im->setCompressionQuality(100);
        $im->readImage($pdf);
        $returnArr = [];

        foreach ($im as $k => $v) {
            $v->setImageFormat('png');
            $fileName = $path . md5($k . time()) . '.png';

            if ($v->writeImage($fileName) == true) {
                $returnArr[] = $fileName;
            }
        }
        return $returnArr;
    }

    /**
     * 将pdf转化为单一png图片
     * 注意使用该函数库首先需要安装imagick扩展及
     * 软件ImageMagic，yum install ImageMagick
     * 然后再安装 yum install -y ghostscript
     * php 通过 Imagic 扩展去调用ImageMagic,ImageMagic去调用 GhostScript 将pdf转换为 png,接着 ImageMagic对png进行处理.
     *
     * @param string $pdf pdf所在路径 （/public/pdf/abc.pdf pdf所在的绝对路径）
     * @param string $path 新生成图片所在路径 (/public/pdf/)
     *
     * @throws Exception
     */
    public static function pdfToOnePng(string $pdf, string $path): string
    {
        try {
            $im = new Imagick();
            $im->setCompressionQuality(100);
            $im->setResolution(120, 120); //设置分辨率 值越大分辨率越高
            $im->readImage($pdf);

            $canvas = new Imagick();
            $imgNum = $im->getNumberImages();
            //$canvas->setResolution(120, 120);
            foreach ($im as $k => $sub) {
                $sub->setImageFormat('png');
                //$sub->setResolution(120, 120);
                $sub->stripImage();
                $sub->trimImage(0);
                $width  = $sub->getImageWidth()  + 10;
                $height = $sub->getImageHeight() + 10;

                if ($k + 1 == $imgNum) {
                    $height += 10;
                } //最后添加10的height
                $canvas->newImage($width, $height, new ImagickPixel('white'));
                $canvas->compositeImage($sub, Imagick::COMPOSITE_DEFAULT, 5, 5);
            }

            $canvas->resetIterator();
            $outPutImgName = $path . microtime(true) . '.png';
            $canvas->appendImages(true)->writeImage($outPutImgName);
            return $outPutImgName;
        } catch (Exception $e) {
            throw new BusinessException(ErrorCode::ERROR, $e->getMessage());
        }
    }
}
