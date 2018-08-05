<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePlayer extends FormRequest
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
            'player_id' => 'exists:players,id',
            'gold' => 'required|integer|min:0',
            'cash' => 'required|integer|min:0',
            'credential' => 'required|integer|between:0,4',
            'detail' => 'nullable|string|max:5000',
            'image' => 'nullable|file|image',
        ];
    }

    public function messages() 
    {
        return [
            'player_id.exists' => 'Jogador não encontrado!', 
            'gold.required' => 'O gold não pode ficar em branco!',
            'gold.integer' => 'O gold tem que ser um número inteiro!',
            'gold.min' => 'O gold tem que ter um valor maior ou igual a 0!',
            'cash.required' => 'O cash não pode ficar em branco!',
            'cash.integer' => 'O cash tem que ser um número inteiro!',
            'cash.min' => 'O cash tem que ter um valor maior ou igual a 0!',
            'credential.required' => 'A credential não pode ficar em branco!',
            'credential.integer' => 'A credential tem que ter um formato valido!',
            'credential.between' => 'A credential que você tentou utilizar não existe!',
            'detail.string' => 'Os detalhes do jogador não estão em um formato valido!',
            'detail.max' => 'Os detalhes do jogador tem um limite de 5000 caracteres!',
            'image.file' => 'Ao fornecer a imagem, verifique se está fornecendo um arquivo!',
            'image.image' => 'Ao fornecer a imagem, verifique se está fornecendo uma imagem!',
        ];
    }
}
