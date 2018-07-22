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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->get('/rpgs/user', function (Request $request) {
    return $request->user()->rpgs()->with('master')->get();
});

Route::get('rpgs', function () {
    return App\Rpg::with('master')->get();
});

Route::get('rpgs/{id}', function ($id) {
    return App\Rpg::find($id);
});

Route::get('/rpgs/{id}/shops', function ($id) {
    return App\Rpg::with('shops.items.players.user')->where('id', $id)->first();
});

Route::get('/rpgs/{id}/players', function ($id) {
    return App\Rpg::with(['players' => function ($query) {
        $query->with(['items', 'user']);
    }])->where('id', $id)->first();
});