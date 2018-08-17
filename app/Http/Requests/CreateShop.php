<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateShop extends FormRequest
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
            'rpg_id' => 'exists:rpgs,id',
            'name' => 'required|string|between:1,25',
            'is_multiple_sale' => 'required|boolean',
        ];
    }

    public function messages() 
    {
        return [
            'rpg_id.exists' => 'Rpg não encontrado!', 
            'name.required' => 'O nome da loja não pode ficar em branco!',
            'name.string' => 'O nome da loja não está em um formato valido!',
            'name.between' => 'O nome da loja deve ter entre 1 e 25 caracteres!',
            'is_multiple_sale.required' => 'Deve informar se a loja é de vendas multiplas ou únicas!',
            'is_multiple_sale.boolean' => 'Deve informar se a loja é de vendas multiplas ou únicas!',
        ];
    }
}
