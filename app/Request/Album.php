<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @see     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class Album extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'get'               => ['id'],
        'captureAlbumImg'   => ['cid', 'aid', 'title'], //采集灵感图片
    ];

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'id'    => 'required|exists:albumlist,id',
            'cid'   => 'required|exists:albumlist,id',
            'aid'   => 'required|gt:0',
            'title' => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => '灵感图片不存在',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
            'cid'       => '采集图片ID',
            'aid'       => '专辑ID',
        ];
    }
}
