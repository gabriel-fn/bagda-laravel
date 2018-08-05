<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateItem extends FormRequest
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
            'item_id' => 'exists:items,id',
            'name' => 'required|string|between:5,25',
            'gold_price' => 'required|integer|min:0',
            'cash_price' => 'required|integer|min:0',
            'max_units' => 'required|integer|min:0',
            'require_test' => 'required|boolean',
            'detail' => 'nullable|string|max:5000',
            'image' => 'nullable|file|image',
        ];
    }

    public function messages() 
    {
        return [
            'item_id.exists' => 'Item não encontrado!', 
            'name.required' => 'O nome do item não pode ficar em branco!',
            'name.string' => 'O nome do item não está em um formato valido!',
            'name.between' => 'O nome do item deve ter entre 5 e 25 caracteres!',
            'gold_price.required' => 'O preço em gold não pode ficar em branco!',
            'gold_price.integer' => 'O preço em gold tem que ser um número inteiro!',
            'gold_price.min' => 'O preço em gold tem que ter um valor maior ou igual a 0!',
            'cash_price.required' => 'O preço em cash não pode ficar em branco!',
            'cash_price.integer' => 'O preço em cash tem que ser um número inteiro!',
            'cash_price.min' => 'O preço em cash tem que ter um valor maior ou igual a 0!',
            'max_units.required' => 'O limite disponível do item não pode ficar em branco!',
            'max_units.integer' => 'O limite disponível do item tem que ser um número inteiro!',
            'max_units.min' => 'O limite disponível do item tem que ter um valor maior ou igual a 0!',
            'require_test.required' => 'Deve informar se o item requer aprovação ou não!',
            'require_test.boolean' => 'Deve informar se o item requer aprovação ou não!',
            'detail.string' => 'Os detalhes do item não estão em um formato valido!',
            'detail.max' => 'Os detalhes do item tem um limite de 5000 caracteres!',
            'image.file' => 'Ao fornecer a imahem, verifique se está fornecendo um arquivo!',
            'image.image' => 'Ao fornecer a imagem, verifique se está fornecendo uma imagem!',
        ];
    }
}
