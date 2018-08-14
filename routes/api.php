<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', 'UserController@show');

Route::middleware('auth:api')->put('/user/reset/password', 'UserController@resetPassword');

Route::post('register', 'Auth\RegisterController@register');

Route::middleware('auth:api')->get('rpgs/user', 'RpgController@index');

Route::get('rpgs', 'RpgController@index');

Route::middleware('auth:api')->get('rpgs/{id}/user', 'RpgController@show');

Route::get('rpgs/{id}', 'RpgController@show');

Route::middleware('auth:api')->get('rpgs/{id}/register', 'RpgController@register');

Route::middleware('auth:api')->post('rpgs/register/response', 'RpgController@registerResponse');

Route::middleware('auth:api')->put('rpgs/items/buy', 'ShopController@buy');

Route::middleware('auth:api')->put('rpgs/items/discard', 'PlayerController@discardItem');

Route::middleware('auth:api')->put('rpgs/requests/dismiss', 'PlayerController@dismissRequest');

Route::middleware('auth:api')->put('rpgs/requests/approve', 'PlayerController@approveRequest');

Route::middleware('auth:api')->prefix('rpgs')->group(function () {
    Route::post('update', 'RpgController@update');
    Route::put('create', 'RpgController@create');
    Route::delete('delete/{id}', 'RpgController@delete');

    Route::prefix('players')->group(function () {
        Route::post('update', 'PlayerController@update');
        Route::delete('delete/{id}', 'PlayerController@delete');
    });

    Route::prefix('shops')->group(function () {
        Route::put('create', 'ShopController@create');
        Route::delete('delete/{id}', 'ShopController@delete');

        Route::prefix('items')->group(function () {
            Route::post('update', 'ItemController@update');
            Route::put('create', 'ItemController@create');
            Route::delete('delete/{id}', 'ItemController@delete');
        });
    });

});
