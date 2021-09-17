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

namespace App\Core\Request;

use Hyperf\Validation\Request\FormRequest;

class Member extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'add'  => ['name'],
        'edit' => ['mobile'],
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
            'mobile' => 'required|unique:member,mobile',
            'name'   => 'required',
        ];
    }
}