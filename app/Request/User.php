<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

class User extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'login'       => ['username', 'password'], //登录
        'bind_mobile' => ['mobile', 'captcha'], //绑定手机
        'profile'     => ['nickname', 'sex', 'qq', 'wx', 'email', 'address', 'content'], //个人资料
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
            'mobile'   => [
                'required',
                Rule::unique('userdata', 'tel')->ignore(user()['id'], 'uid'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'captcha'  => 'required|digits:6',
            'qq'       => 'digits_between:6,12',
            'wx'       => 'string',
            'email'    => [
                'required',
                'email',
                Rule::unique('userdata', 'email')->ignore(user()['id'], 'uid'),
            ],
            'address'  => 'alpha_num',
            'sex'      => 'digits:1',
            'content'  => 'alpha_num',
            'nickname' => 'required|alpha_num',
        ];
    }
}
