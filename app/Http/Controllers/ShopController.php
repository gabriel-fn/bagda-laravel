<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\BuyShop;
use App\Item;
use App\Shop;
use App\Rpg;

class ShopController extends Controller
{
    public function buy(BuyShop $request)
    {
        $response = ['error' => false, 'message' => 'Compra realizada com sucesso!'];

        $item = Item::findOrFail($request->item_id);
        $item->load('shop.rpg');
        $rpg = Rpg::findOrFail($item->shop->rpg->id);

        $this->authorize('buy', $rpg);

        $players_with_item = $item->players()->get();
        $total_units = $players_with_item->reduce(function($total, $player) {
            return $total += $player->process->units;
        }, 0);

        if ($item->max_units && ($total_units >= $item->max_units)) {
            //Caso tenha atingido o maximo de unidades permitidas pelo item
            $response['error'] = true;
            $response['message'] = 'O item que você tentou comprar atingiu seu limite de usuários!';
        } else {
            $rpg = $request->user()->rpgs()->wherePivot('rpg_id', $item->shop->rpg->id)->first();
            if (($rpg->player->gold < $item->gold_price) || ($rpg->player->cash < $item->cash_price)) {
                //Caso ele não tenha dinheiro suficiente
                $response['error'] = true;
                $response['message'] = 'Você não tem gold ou cash o suficiente para arcar com os custos deste item!';
            } else {
                if ($item->require_test) {
                    //Caso o item requer teste, será feito o pedido de compra para ser aprovado pelo mestre
                    $rpg->player->requests()->syncWithoutDetaching($item->id);
                    $response['message'] = 'Seu pedido foi solicitado com sucesso, aguarde aprovação! Caso negado, seu dinheiro será devolvido!';
                } else {
                    if (!$item->shop->is_multiple_sale) {
                        //No caso da loja não aceitar que um player tenha mais de um item da mesma, apague todo vinculo que envolva os itens daquela loja.
                        $array_items_id = $item->shop->items()->get()->map(function($item) {
                            return $item->id;
                        });
                        $rpg->player->items()->detach($array_items_id);
                    }

                    $item_player = $rpg->player->items()->wherePivot('item_id', $item->id)->first();
    
                    if ($item_player) {
                        //Caso o item já tenha sido comprado, irá acrescentar uma unit
                        $item_player->process->units += 1;
                        $item_player->process->save();
                    } else {
                        //Caso não tenha outro item igual no inventário, será criado um novo relacionamento
                        $rpg->player->items()->attach([$item->id => ['units' => 1]]);
                    }
                    $rpg->player->gold -= $item->gold_price;
                    $rpg->player->cash -= $item->cash_price;
                    $rpg->player->save();
                }
            }
        }
        return $response;
    }
}
