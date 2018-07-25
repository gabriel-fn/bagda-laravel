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
    $item = App\Item::find(15);
    $item->load('shop.rpg');
    $array_items_id = $item->shop->items()->get()->map(function($item) {
        return $item->id;
    });
    return $array_items_id;
});

Route::get('item/{id}/buy', function ($id) {

    $user = App\User::find(1);
    $response = ['error' => false, 'message' => '', 'data' => null];

    if (!$user) {
        //Caso usuário não autenticado
        $response['error'] = true;
        $response['message'] = 'Tentativa de realização de compra não estando autenticado!'; 
    } else {
        $item = App\Item::find($id);
        if (!$item) {
            //Caso item não exista
            $response['error'] = true;
            $response['message'] = 'O item que você tentou comprar está fora de linha!';
        } else {
            $players_with_item = $item->players()->get();
            $total_units = $players_with_item->reduce(function($total, $player) {
                return $total += $player->process->units;
            }, 0);
            if ($item->max_units && ($total_units >= $item->max_units)) {
                //Caso tenha atingido o maximo de unidades permitidas pelo item
                $response['error'] = true;
                $response['message'] = 'O item que você tentou comprar atingiu seu limite de usuários!';
            } else {
                $item->load('shop.rpg');
                $rpg = $user->rpgs()->wherePivot('rpg_id', $item->shop->rpg->id)->first();
                if (!$rpg) {
                    //Caso não exista relação entre o usuário e o rpg (não é player)
                    $response['error'] = true;
                    $response['message'] = 'Você não participa do rpg ao qual este item está disponível!';
                } else {
                    if ($rpg->player->credential === 0) {
                        //Caso jogador com inscrição ainda não aprovada
                        $response['error'] = true;
                        $response['message'] = 'Você só poderá comprar items quando sua inscrição for aprovada pelo mestre do rpg!';
                    } else {
                        if (($rpg->player->gold < $item->gold_price) || ($rpg->player->cash < $item->cash_price)) {
                            //Caso ele não tenha dinheiro suficiente
                            $response['error'] = true;
                            $response['message'] = 'Você não tem gold ou cash o suficiente para arcar com os custos deste item!';
                        } else {
                            $item_player = $rpg->player->items()->wherePivot('item_id', $item->id)->first();

                            if ($item_player) {
                                //Caso o item já tenha sido comprado, irá validar o status da compra e talvez acrescentar uma unit
                                if (!$item_player->process->status) {
                                    //Caso a compra já esteja em processo de avaliação
                                    $response['error'] = true;
                                    $response['message'] = 'Você já tem uma compra deste item sendo processada, aguarde até a sua primeira compra ser validada!';
                                } else {
                                    $item_player->process->units += 1;
                                    $item_player->process->save();

                                    $rpg->player->gold -= $item->gold_price;
                                    $rpg->player->cash -= $item->cash_price;
                                    $rpg->player->save();
                                    $response['data'] = $user->rpgs()->with(['master', 'shops.items.players.user', 'players' => function ($query) {
                                        $query->with(['items', 'user']);
                                    }])->wherePivot('rpg_id', $item->shop->rpg->id)->first();
                                }
                            } else {
                                $rpg->player->items()->attach([
                                    $item->id => [
                                        'status' => !$item->require_test, 
                                        'units' => 1,
                                    ]
                                ]);
                                $rpg->player->gold -= $item->gold_price;
                                $rpg->player->cash -= $item->cash_price;
                                $rpg->player->save();
                                $response['data'] = $user->rpgs()->with(['master', 'shops.items.players.user', 'players' => function ($query) {
                                    $query->with(['items', 'user']);
                                }])->wherePivot('rpg_id', $item->shop->rpg->id)->first();
                            }
                        }
                    }
                }
            }
        }
    }
    return $response;
});