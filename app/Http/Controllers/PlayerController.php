<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ItemPlayer;
use App\Http\Requests\UpdatePlayer;
use App\Item;
use App\Player;
use App\Rpg;

class PlayerController extends Controller
{
    public function update(UpdatePlayer $request) 
    {
        $response = ['error' => false, 'message' => 'Jogador atualizado com sucesso!'];
        $player = Player::findOrFail($request->player_id);
        $this->authorize('update', $player);
        $player->update($request->only('credential', 'gold', 'cash', 'detail'));
        if ($request->has('image')) {
            $player->makeDirectory();
            $player->deleteImage();
            $request->file('image')->storeAs('images/rpgs/'.$player->rpg->id.'/players', $player->id.'.jpg');
        }
        return $response;
    }

    public function delete($id) 
    {
        $response = ['error' => false, 'message' => 'Jogador deletado com sucesso!'];
        $player = Player::find($id);
        if (!$player) {
            $response = ['error' => true, 'message' => 'Jogador não encontrado!'];
        } else {
            $this->authorize('update', $player);
            $player->delete();
            $player->deleteImage();
        }
        return $response;
    }

    public function discardItem(ItemPlayer $request) 
    {
        $response = ['error' => false, 'message' => 'Item descartado com sucesso.'];
    
        $item = Item::findOrFail($request->item_id);
        $item->load('shop.rpg');
        $player = Player::where('id', $request->player_id)->where('rpg_id', $item->shop->rpg->id)->first();

        $this->authorize('inventary', $player);
 
        $player_item = $item->players()->wherePivot('player_id', $player->id)->first();
        if (!$player_item) {
            //Caso o jogador não apresente relacionamento com o item.
            $response = ['error' => true, 'message' => 'O jogador não está em posse deste item!'];
        } else {
            if ($player_item->process->units > 1) {
                $player_item->process->units -= 1;
                $player_item->process->save();
            } else {
                $player_item->items()->detach($item->id);
            }
        }
        $player->load('items', 'user', 'requests');
        $response['data'] = $player; 
        return $response; 
    }

    public function dismissRequest(ItemPlayer $request) 
    {
        $response = ['error' => false, 'message' => 'Pedido cancelado com sucesso.'];
        
        $item = Item::findOrFail($request->item_id);
        $item->load('shop.rpg');
        $player = Player::where('id', $request->player_id)->where('rpg_id', $item->shop->rpg->id)->first();

        $this->authorize('inventary', $player);

        $item->requests()->detach($player->id);

        $player->load('items', 'user', 'requests');
        $response['data'] = $player; 
        return $response; 
    }

    public function approveRequest(ItemPlayer $request) {
        $response = ['error' => false, 'message' => 'Pedido aprovado com sucesso.'];

        $item = Item::findOrFail($request->item_id);
        $item->load('shop.rpg');
        $player = Player::where('id', $request->player_id)->where('rpg_id', $item->shop->rpg->id)->first();
        
        $this->authorize('update', $player);

        $players_with_item = $item->players()->get();
        $total_units = $players_with_item->reduce(function($total, $player) {
            return $total += $player->process->units;
        }, 0);

        if ($item->max_units && ($total_units >= $item->max_units)) {
            //Caso tenha atingido o maximo de unidades permitidas pelo item
            $response['error'] = true;
            $response['message'] = 'O item atingiu seu limite de usuários!';
        } else {
            if (($player->gold < $item->gold_price) || ($player->cash < $item->cash_price)) {
                //Caso ele não tenha dinheiro suficiente
                $response['error'] = true;
                $response['message'] = 'O jogador não tem gold ou cash o suficiente para arcar com os custos deste item!';
            } else {
                if (!$item->shop->is_multiple_sale) {
                    //No caso da loja não aceitar que um player tenha mais de um item da mesma, apague todo vinculo que envolva os itens daquela loja.
                    $array_items_id = $item->shop->items()->get()->map(function($item) {
                        return $item->id;
                    });
                    $player->items()->detach($array_items_id);
                }
                $item_player = $player->items()->wherePivot('item_id', $item->id)->first();
    
                if ($item_player) {
                    //Caso o item já tenha sido comprado, irá acrescentar uma unit
                    $item_player->process->units += 1;
                    $item_player->process->save();
                } else {
                    //Caso não tenha outro item igual no inventário, será criado um novo relacionamento
                    $player->items()->attach([$item->id => ['units' => 1]]);
                }
                $player->requests()->detach($item->id);
                $player->gold -= $item->gold_price;
                $player->cash -= $item->cash_price;
                $player->save();
            }
        }
        $player->load('items', 'user', 'requests');
        $response['data'] = $player; 
        return $response;
    }
}
