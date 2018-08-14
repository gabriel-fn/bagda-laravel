<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function show(Request $request)
    {
        return $request->user()->load('players.rpg');
    }

    public function resetPassword(Request $request) {
        Validator::make(
            $request->all(),
            [
                'password' => 'required|string|between:4,12|confirmed'
            ],
            [
                'password.required' => 'A senha deve estar preenchida!',
                'password.string' => 'A senha deve estar em um formato valido!',
                'password.between' => 'A senha deve ter entre 4 e 12 caracteres!',
                'password.confirmed' => 'A senha e sua confirmação estão diferentes!'
            ]
        )->validate();

        $response = ['error' => false, 'message' => 'Senha trocada com sucesso!'];
        $request->user()->update(['password' => Hash::make($request->password)]);
        return $response;
    }
}
