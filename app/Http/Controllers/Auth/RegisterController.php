<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/login';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|between:1,25|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|between:4,12|confirmed',
        ],
        [
            'name.required' => 'O nome deve estar preenchido!',
            'name.string' => 'O nome deve estar em um formato valido!',
            'name.between' => 'O nome deve ter entre 1 e 25 caracteres!',
            'name.unique' => 'Este nome já registrado!',
            'email.required' => 'O email deve estar preenchido!',
            'email.string' => 'O email deve estar em um formato valido!',
            'email.email' => 'O email deve estar em um formato valido!',
            'email.max' => 'O email deve ter no máximo 255 caracteres!',
            'email.unique' => 'Este e-mail já registrado!',
            'password.required' => 'A senha deve estar preenchida!',
            'password.string' => 'A senha deve estar em um formato valido!',
            'password.between' => 'A senha deve ter entre 4 e 12 caracteres!',
            'password.confirmed' => 'A senha e sua confirmação estão diferentes!',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $input = $request->all();
        $this->validator($input)->validate();
        $user = $this->create($input);
        $response = ['error' => false, 'message' => 'Usuário cadastrado com sucesso!'];
        return $response;
    }

}
