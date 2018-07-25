<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;

class ShopController extends Controller
{
    public function buy($id, Request $request)
    {
        $response = ['error' => false, 'message' => ''];
    
        if (!$request->user()) {
            //Caso usuário não autenticado
            $response['error'] = true;
            $response['message'] = 'Tentativa de realização de compra não estando autenticado!'; 
        } else {
            $item = Item::find($id);
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
                    $rpg = $request->user()->rpgs()->wherePivot('rpg_id', $item->shop->rpg->id)->first();
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
                                if ($item->require_test) {
                                    //Item requer aprovação. O dinheiro será retirado e, caso pedido seja desaprovado, devolvido.
                                    $rpg->player->requests()->attach($item->id);
                                    $rpg->player->gold -= $item->gold_price;
                                    $rpg->player->cash -= $item->cash_price;
                                    $rpg->player->save();
                                    $response['message'] = 'Seu pedido foi solicitado com sucesso, aguarde aprovação! Caso negado, seu dinheiro será devolvido!';
                                } else {
                                    $item_player = $rpg->player->items()->wherePivot('item_id', $item->id)->first();
    
                                    if ($item_player) {
                                        //Caso o item já tenha sido comprado, irá acrescentar uma unit
                                        $item_player->process->units += 1;
                                        $item_player->process->save();
        
                                        $rpg->player->gold -= $item->gold_price;
                                        $rpg->player->cash -= $item->cash_price;
                                        $rpg->player->save();
                                        $response['message'] = 'Compra realizada com sucesso!';
                                        
                                    } else {
                                        //Caso não tenha outro item igual no inventário, será criado um novo relacionamento
                                        $rpg->player->items()->attach([$item->id => ['units' => 1]]);
                                        $rpg->player->gold -= $item->gold_price;
                                        $rpg->player->cash -= $item->cash_price;
                                        $rpg->player->save();
                                        $response['message'] = 'Compra realizada com sucesso!';
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
        return $response;
    }
}
