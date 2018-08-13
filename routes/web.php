<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('make/rpg/{senha}', function ($senha) {
    if ($senha == 'spsp1010') {
        factory(App\Rpg::class)->create([
            'name' => 'Rpg sem nome', 
            'is_public' => false,
            'user_id' => 1
        ]);
    }
});