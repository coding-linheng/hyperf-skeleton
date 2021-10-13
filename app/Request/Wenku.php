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
use Hyperf\Validation\Rule;

class Wenku extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'get'               => ['id'],
        'list'              => ['query', 'order', 'lid', 'mulu_id', 'labels'],
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
            'id'      => 'required|exists:wenku,id',
            'lid'     => Rule::in([0, 1, 2]),
            'mulu_id' => 'digits',
            'order'   => Rule::in(['g_time', 'id', 'dtime', 'tui', 'downnum']),
            'labels'  => 'string',
            'query'   => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists' => '专辑不存在',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
        ];
    }
}
