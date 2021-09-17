<?php

declare(strict_types=1);

namespace App\Core\Request;

use Hyperf\Validation\Request\FormRequest;

class User extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'login' => ['username', 'password'],
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
            'username' => 'required|alpha_num',
            'password' => 'required|digits_between:6,20',
        ];
    }
}
