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
            $request->file('image')->storeAs('images/players', $player->id.'.jpg');
        }
        return $response;
    }

    public function delete($id) 
    {
        $response = ['error' => false, 'message' => 'Jogador deletado com sucesso!'];
        $player = Player::findOrFail($id);
        if (!$player) {
            $response = ['error' => true, 'message' => 'Jogador não encontrado!'];
        } else {
            $this->authorize('update', $player);
            $player->delete();
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
}
