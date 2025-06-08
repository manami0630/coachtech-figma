<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'description' => 'required|max:255',
            'condition' => 'required',
            'price' => 'required|min:0|integer',
            'brand_name' => 'nullable',
            'image' => 'required|image|mimes:jpeg,jpg,png',
            'categories' => 'required|array',
            'categories.*' => 'exists:categories,id',
        ];
    }
    public function messages()
    {
        return [
            'name.required' => '商品名を入力してください',
            'description.required' => '商品の説明を入力してください',
            'description.max' => '商品の説明は255文字以内で入力してください',
            'categories.required' => 'カテゴリーを選択してください',
            'image.required' => '画像を選択してください',
            'image.mimes:png,jpeg' => '「.png」または「.jpeg」形式でアップロードしてください',
            'condition.required' => '商品の状態を選択してください',
            'price.required' => '商品価格を入力してください',
            'price.min' => '価格は0円以上で入力してください',
            'price.integer' => '価格は数字で入力してください',
        ];
    }
}
