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

class Album extends FormRequest
{
    /**
     * Set scene values.
     */
    public $scenes = [
        'get'               => ['id'],
        'captureAlbumImg'   => ['cid', 'aid', 'title'], //采集灵感图片
        'addAlbum'          => ['name', 'fenlei', 'isoriginal', 'isopensale', 'brand_scenes', 'brand_name', 'brand_use', 'paint_country', 'paint_name', 'paint_style', 'label'], //创建专辑
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
            'id'            => 'required|exists:albumlist,id',
            'cid'           => 'required|exists:albumlist,id',
            'aid'           => 'required|gt:0',
            'title'         => 'string',
            'name'          => 'required|alpha_dash|between:1,50',
            'fenlei'        => 'required|exists:lingfenlei,id',
            'isoriginal'    => ['required', Rule::in([1, 2])],
            'isopensale'    => Rule::in([1, 2]),
            'brand_scenes'  => 'string',
            'brand_name'    => 'string',
            'brand_use'     => 'string',
            'paint_country' => 'string',
            'paint_name'    => 'string',
            'paint_style'   => 'string',
            'label'         => 'string',
        ];
    }

    public function messages(): array
    {
        return [
            'id.exists'           => '灵感图片不存在',
            'fenlei.exists'       => ':attribute 不存在',
        ];
    }

    /**
     * 获取验证错误的自定义属性.
     */
    public function attributes(): array
    {
        return [
            'cid'           => '采集图片ID',
            'aid'           => '专辑ID',
            'name'          => '专辑名称',
            'fenlei'        => '分类',
            'isoriginal'    => '是否原创专辑',
            'isopensale'    => '是否售卖',
            'brand_scenes'  => '品牌场景',
            'brand_name'    => '品牌名称',
            'brand_use'     => '品牌应用',
            'paint_country' => '绘画国家',
            'paint_name'    => '绘画名称',
            'paint_style'   => '绘画风格',
            'label'         => '标签',
        ];
    }
}
