<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

/*
 * 举报验证器
 */

class Report extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'report_material'    => ['material_id', 'text', 'email'],
        'complaint_material' => ['material_id', 'text'],
        'report_library'     => ['library_id', 'text', 'email'],
        'complaint_library'  => ['library_id', 'text'],
        'report_album'       => ['album_id', 'text'],
        'report_image'       => ['user_id', 'text'],
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
            'material_id' => 'required|exists:img,id',
            'library_id'  => 'required|exists:wenku,id',
            'album_id'    => 'required|exists:albumlist,id',
            'user_id'     => 'required|exists:user,id',
            'text'        => ['required'],
            'email'       => ['required', 'email'],
        ];
    }

    public function attributes(): array
    {
        return [
            'material_id' => '素材',
            'library_id'  => '文库',
            'type'        => '类型',
            'text'        => '举报内容',
            'email'       => '邮箱',
        ];
    }
}
