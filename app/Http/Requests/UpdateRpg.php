<?php

namespace App\Http\Requests;

use App\Rpg;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRpg extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $rpg = Rpg::find($this->rpg_id);
        return $rpg && $this->user()->can('update', $rpg);
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
            'name' => 'required|string|between:5,25',
            'gold_starter' => 'required|integer|min:0',
            'cash_starter' => 'required|integer|min:0',
            'is_public' => 'required|boolean',
            'image' => 'nullable|file|image',
        ];
    }

    public function messages() 
    {
        return [
            'rpg_id.exists' => 'Rpg não encontrado! Você tentou atualizar um rpg inexistente.', 
            'name.required' => 'O nome do rpg não pode ficar em branco!',
            'name.string' => 'O nome do rpg não está em um formato valido!',
            'name.between' => 'O nome do rpg deve ter entre 5 e 25 caracteres!',
            'gold_starter.required' => 'O gold inicial não pode ficar em branco!',
            'gold_starter.integer' => 'O gold inicial tem que ser um número inteiro!',
            'gold_starter.min' => 'O gold inicial tem que ter um valor maior ou igual a 0!',
            'cash_starter.required' => 'O cash inicial não pode ficar em branco!',
            'cash_starter.integer' => 'O cash inicial tem que ser um número inteiro!',
            'cash_starter.min' => 'O cash inicial tem que ter um valor maior ou igual a 0!',
            'is_public.required' => 'Deve informar de o rpg é público ou não!',
            'is_public.boolean' => 'Deve informar de o rpg é público ou não!',
            'image.file' => 'Ao fornecer a capa, verifique se está fornecendo um arquivo!',
            'image.image' => 'Ao fornecer a capa, verifique se está fornecendo uma image!',
        ];
    }
}
