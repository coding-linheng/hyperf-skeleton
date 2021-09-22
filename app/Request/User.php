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
        'login'         => ['username', 'password'], //登录
        'bind_mobile'   => ['mobile', 'captcha'], //绑定手机
        'profile'       => ['nickname', 'sex', 'wx', 'address', 'content'], //个人资料
        'certification' => ['name', 'mobile', 'id_card', 'alipay', 'qq', 'email', 'id_card_true', 'id_card_false'],
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
            'username'      => 'required|alpha_num',
            'password'      => 'required|digits_between:6,20',
            'mobile'        => [
                'required',
                Rule::unique('userdata', 'tel')->ignore(user()['id'], 'uid'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'captcha'       => 'required|digits:6',
            'qq'            => 'digits_between:6,12',
            'wx'            => 'string',
            'email'         => [
                'required',
                'email',
                Rule::unique('userdata', 'email')->ignore(user()['id'], 'uid'),
            ],
            'address'       => 'alpha_num',
            'sex'           => 'digits:1',
            'content'       => 'alpha_num',
            'nickname'      => 'required|alpha_num',
            'name'          => ['required', 'regex:/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', 'between:2,8'],
            'id_card'       => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
            'alipay'        => 'required|string',
            'id_card_true'  => 'required|active_url',
            'id_card_false' => 'required|active_url',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
            'name'          => '真实姓名',
            'id_card'       => '身份证',
            'alipay'        => '支付宝',
            'id_card_true'  => '身份证正面',
            'id_card_false' => '身份证反面',
        ];
    }
}
