<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
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
<<<<<<< Updated upstream
            'payment_method' => 'required',
            'item_id' => 'required|integer',
            'postal_code' => 'required|regex:/^\d{3}-\d{4}$/',
            'address' => 'required',
            'building_name' => 'nullable',
=======
            'item_name' => 'required|string|max:255',
            'amount' => 'required|integer|min:1',
            'payment_method' => 'required|string',
            'item_id' => 'required|string',
            'postal_code' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'building_name' => 'nullable|string|max:255',
>>>>>>> Stashed changes
        ];
    }
    public function messages()
    {
        return [
            'payment_method.required' => '支払方法を選択してください',
            'postal_code.required' => '郵便番号を入力してください',
            'postal_code.regex' => '郵便番号はハイフンありの8文字で入力してください',
            'address.required' => '住所を入力してください',
        ];
    }
}
