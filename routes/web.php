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

Route::get('image/path', function () {

    return asset('storage/images/rpgs');
});

Route::get('item/discard', function () {
    $item = App\Item::with(['shop.rpg', 'players' => function ($query) {
        $query->where('player_id', 1);
    }])->where('id', 17)->first();

    return $item;
});