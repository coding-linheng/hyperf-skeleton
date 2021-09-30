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
        'profile'       => ['nickname', 'sex', 'wx', 'address', 'qq', 'content'], //个人资料
        'certification' => ['name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1'],
        'notice'        => ['notice_id'],
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
                @Rule::unique('user', 'mobile')->ignore(user()['id'], 'uid'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'tel' => [
                'required',
                @Rule::unique('userdata', 'tel')->ignore(user()['id'], 'uid'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'captcha' => 'required|digits:6',
            'qq'      => 'digits_between:6,12',
            'wx'      => 'string',
            'email'   => [
                'required',
                'email',
                @Rule::unique('userdata', 'email')->ignore(user()['id'], 'uid'),
            ],
            'address'   => 'alpha_num',
            'sex'       => Rule::in([1, 2]),
            'content'   => 'alpha_num',
            'nickname'  => 'required|alpha_num',
            'name'      => ['required', 'regex:/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', 'between:2,8'],
            'cardnum'   => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
            'zhi'       => 'required|string',
            'cardimg'   => 'required|active_url',
            'cardimg1'  => 'required|active_url',
            'notice_id' => 'exists:notice,id',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
            'name'     => '真实姓名',
            'cardnum'  => '身份证',
            'zhi'      => '支付宝',
            'cardimg'  => '身份证正面',
            'cardimg1' => '身份证反面',
        ];
    }
}
