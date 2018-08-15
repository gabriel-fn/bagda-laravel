<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRpg extends FormRequest
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
            'name' => 'required|string|between:5,25',
            'gold_starter' => 'required|integer|min:0',
            'cash_starter' => 'required|integer|min:0',
            'is_public' => 'required|boolean',
        ];
    }

    public function messages() 
    {
        return [
            'name.required' => 'O nome do rpg não pode ficar em branco!',
            'name.string' => 'O nome do rpg não está em um formato valido!',
            'name.between' => 'O nome do rpg deve ter entre 5 e 25 caracteres!',
            'gold_starter.required' => 'O gold inicial não pode ficar em branco!',
            'gold_starter.integer' => 'O gold inicial tem que ser um número inteiro!',
            'gold_starter.min' => 'O gold inicial tem que ter um valor maior ou igual a 0!',
            'cash_starter.required' => 'O cash inicial não pode ficar em branco!',
            'cash_starter.integer' => 'O cash inicial tem que ser um número inteiro!',
            'cash_starter.min' => 'O cash inicial tem que ter um valor maior ou igual a 0!',
            'is_public.required' => 'Deve informar se o rpg é público ou não!',
            'is_public.boolean' => 'Deve informar se o rpg é público ou não!',
        ];
    }
}
