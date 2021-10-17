<?php

declare(strict_types=1);

namespace App\Controller;

use App\Model\Picture;
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
        //保存图片在本地并入库picture表
        if (in_array($file->getExtension(), ['jpg', 'png']) && !Picture::query()->where('name', $data['name'])->exists()) {
            if (!is_dir(BASE_PATH . '/public/uploads')) {
                mkdir(BASE_PATH . '/public/uploads', 0777, true);
            }
            $file->moveTo(BASE_PATH . '/' . $data['path']);
            $id = Picture::insertGetId([
                'name'        => $data['name'],
                'path'        => $data['path'],
                'url'         => $data['image_url'],
                'status'      => 1,
                'create_time' => time(),
                'update_time' => time(),
            ]);
        } else {
            $id = Picture::query()->where('name', $data['name'])->value('id');
        }
        return $this->success(array_merge($data, ['pic_id' => $id]));
    }
}
