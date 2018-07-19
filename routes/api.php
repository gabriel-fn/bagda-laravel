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

Route::get('reports', function () {
    return App\Report::with('rpg')->get();
});

Route::get('/rpgs/{id}/reports', function ($id) {
    return App\Report::with('rpg')->whereHas('rpg', function ($query) use ($id) {
        $query->where('id', $id);
    })->get();
});

Route::get('/rpgs/{id}/shops', function ($id) {
    return App\Rpg::with('shops.items.users')->where('id', $id)->first();
});

Route::get('/rpgs/{id}/players', function ($id) {
    return App\Rpg::with(['players.items'])->where('id', $id)->first();
});

Route::get('/rpgs/{id}/quests', function ($id) {
    return App\Quest::with(['author', 'rpg', 'items', 'players'])->whereHas('rpg', function ($query) use ($id) {
        $query->where('id', $id);
    })->get();
});

Route::get('rpgs', function () {
    return App\Rpg::with('master')->get();
});

Route::get('user/{id}/rpgs', function ($id) {
    $user = App\User::find($id);
    return $user->rpgs()->with('master')->get();
});

Route::get('rpgs/{id}', function ($id) {
    return App\Rpg::with(['quests', 'shops.items', 'players'])->where('id', $id)->first();
});