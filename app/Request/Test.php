<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\DbConnection\Db;
use Hyperf\Validation\Request\FormRequest;
use Hyperf\Validation\Rule;

/**
 * 验证器使用示例
 */
class Test extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'login'               => ['username', 'password'], //登录
        'bind_mobile'         => ['mobile', 'captcha'], //绑定手机
        'profile'             => ['nickname', 'sex', 'wx', 'address', 'qq', 'content'], //个人资料
        'certification'       => ['name', 'tel', 'cardnum', 'zhi', 'qq', 'email', 'cardimg', 'cardimg1'],
        'notice'              => ['notice_id'],
        'upload_head'         => ['head_image'],
        'work'                => ['status'],
        'upload'              => ['upload', 'type'],
        'del_material'        => ['material_id'],
        'get_library'         => ['library_id'],
        'batch_del_material'  => ['material_ids'],
        'batch_del_library'   => ['library_ids'],
        'get_material'        => ['material_id'],
        'information'         => ['material_id', 'img', 'mulu_id', 'geshi_id', 'title', 'leixing', 'price', 'guanjianci'],
        'information_library' => ['library_id', 'img', 'free_num', 'title', 'guanjianci', 'leixing', 'price'],
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
            'username'     => 'required|alpha_num',
            'password'     => 'required|digits_between:6,20',
            'mobile'       => [
                'required',
                @Rule::unique('user', 'mobile')->ignore(user()['id'], 'id'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'tel'          => [
                'required',
                @Rule::unique('userdata', 'tel')->ignore(user()['id'], 'uid'),
                'regex:((\d{11})|^((\d{7,8})|(\d{4}|\d{3})-(\d{7,8})|(\d{4}|\d{3})-(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1})|(\d{7,8})-(\d{4}|\d{3}|\d{2}|\d{1}))$)',
            ],
            'captcha'      => 'required|digits:6',
            'qq'           => 'digits_between:6,12',
            'wx'           => 'string',
            'email'        => [
                'required',
                'email',
                @Rule::unique('userdata', 'email')->ignore(user()['id'], 'uid'),
            ],
            'address'      => 'alpha_num',
            'sex'          => Rule::in([1, 2]),
            'content'      => 'alpha_num',
            'nickname'     => 'required|alpha_num',
            'name'         => ['required', 'regex:/^[\x{4e00}-\x{9fa5}A-Za-z0-9_]+$/u', 'between:2,8'],
            'cardnum'      => ['required', 'regex:/(^\d{15}$)|(^\d{18}$)|(^\d{17}(\d|X|x)$)/'],
            'zhi'          => 'required|string',
            'cardimg'      => 'required|active_url',
            'cardimg1'     => 'required|active_url',
            'notice_id'    => 'exists:notice,id',
            'head_image'   => 'required|active_url',
            'status'       => ['required', Rule::in([0, 1, 2, 3, 4])],
            'type'         => ['required', Rule::in([1, 2])],
            'upload'       => 'required|file',
            'img'          => ['required', 'images'],
            'mulu_id'      => 'required|exists:mulu,id',   //分类
            'fenlei'       => 'required|exists:fenlei,id', //类型
            'guanjianci'   => 'required|key_words', //类型
            'free_num'     => 'required|integer', //免费页数
            'theme'        => [
                @Rule::requiredIf(function () {
                    return Db::table('scthreefenlei')->where('mid', $this->post('fenlei'))->exists();
                }), 'exists:scthreefenlei,id',
            ], //主题
            'geshi_id'     => 'required|exists:geshi,id',  //格式
            'title'        => 'required|alpha_dash|between:1,30',
            'leixing'      => ['required', Rule::in([1, 2])],
            'price'        => [Rule::requiredIf($this->post('leixing') == 2), 'integer', 'between:1,20'],
            'material_id'  => [
                'required',
                @Rule::exists('img', 'id')->where(fn ($query) => $query->where(['uid' => user()['id']])),
            ],
            'library_id'   => [
                'required',
                @Rule::exists('wenku', 'id')->where(fn ($query) => $query->where(['uid' => user()['id']])),
            ],
            'material_ids' => 'required',
            'library_ids'  => 'required',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
            'name'         => '真实姓名',
            'cardnum'      => '身份证',
            'zhi'          => '支付宝',
            'cardimg'      => '身份证正面',
            'cardimg1'     => '身份证反面',
            'head_image'   => '头像',
            'status'       => '状态',
            'type'         => '类型',
            'material_id'  => '素材',
            'library_id'   => '文库',
            'material_ids' => '素材',
            'library_ids'  => '文库',
            'img'          => '图片',
            'mulu_id'      => '素材分类',
            'fenlei'       => '素材类型',
            'geshi_id'     => '素材格式',
            'title'        => '标题',
            'leixing'      => '类型',
            'price'        => '价格',
            'guanjianci'   => '关键词',
            'free_num'     => '免费页数',
        ];
    }

    public function messages(): array
    {
        return [
            'material_id.exists'   => '素材不存在或不属于你',
            'library_id.exists'    => '文库不存在或不属于你',
            'img.exists'           => '图片不存在',
            'mulu_id.exists'       => ':attribute 不存在',
            'fenlei.exists'        => ':attribute 不存在',
            'geshi_id.exists'      => ':attribute 不存在',
            'guanjianci.key_words' => ':attribute 数量错误',
        ];
    }
}
