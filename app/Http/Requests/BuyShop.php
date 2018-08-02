<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BuyShop extends FormRequest
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
            'item_id' => 'exists:items,id'
        ];
    }

    public function messages() 
    {
        return [
            'item_id.exists' => 'Item não encontrado!'
        ];
    }
}
