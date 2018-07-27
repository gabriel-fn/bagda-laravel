<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use App\Player;

class PlayerController extends Controller
{
    public function discardItem(Request $request) {
        $response = ['error' => false, 'message' => 'Item descartado com sucesso.'];
        
        if (!$request->filled(['player_id', 'item_id'])) {
            //Caso as variáriaveis não estejam presentes
            $response = ['error' => true, 'message' => 'Solicitação não cumpre os requisitos mínimos!'];
        } else{
            $item_id = $request->input('item_id');
            $player_id = $request->input('player_id');
            $item = Item::with('shop.rpg')->where('id', $item_id)->first();
            if (!$item) {
                //Caso o item não exista
                $response = ['error' => true, 'message' => 'O item desta solicitação não foi encontrado!'];
            } else {
                $user_request = $request->user()->rpgs()->wherePivot('rpg_id', $item->shop->rpg->id)->first();
                if (!$user_request) {
                    //Caso o usuário requisitante não tenha vinculo com o rpg
                    $response = ['error' => true, 'message' => 'O usuário não apresenta nenhum relacionamento com este rpg.'];
                } else {
                    if ($user_request->player->id !== $player_id && $user_request->player->credential < 2) {
                        //Caso o usuário que requisita o descarte não seja nem moderador e nem possuidor do item
                        $response = ['error' => true, 'message' => 'O usuário não tem autoridade para descartar este item!'];
                    } else {
                        $player = $item->players()->wherePivot('player_id', $player_id)->first();
                        if (!$player) {
                            //Caso o jogador não apresente relacionamento com o item.
                            $response = ['error' => true, 'message' => 'O jogador não está em posse deste item!'];
                        } else {
                            if ($player->process->units > 1) {
                                $player->process->units -= 1;
                                $player->process->save();
                            } else {
                                $player->items()->detach($item->id);
                            }
                        }
                    }
                }
            }
        }
        return $response; 
    }

    public function dismissRequest(Request $request) {
        $response = ['error' => false, 'message' => 'Pedido cancelado com sucesso.'];
        
        if (!$request->filled(['player_id', 'item_id'])) {
            //Caso as variáriaveis não estejam presentes
            $response = ['error' => true, 'message' => 'Solicitação não cumpre os requisitos mínimos!'];
        } else{
            $item_id = $request->input('item_id');
            $player_id = $request->input('player_id');
            $item = Item::with('shop.rpg')->where('id', $item_id)->first();
            if (!$item) {
                //Caso o item não exista
                $response = ['error' => true, 'message' => 'O item desta solicitação não foi encontrado!'];
            } else {
                $user_request = $request->user()->rpgs()->wherePivot('rpg_id', $item->shop->rpg->id)->first();
                if (!$user_request) {
                    //Caso o usuário requisitante não tenha vinculo com o rpg
                    $response = ['error' => true, 'message' => 'O usuário não apresenta nenhum relacionamento com este rpg.'];
                } else {
                    if ($user_request->player->id !== $player_id && $user_request->player->credential < 2) {
                        //Caso o usuário que requisita o cancelamento não seja nem moderador e dono do pedido
                        $response = ['error' => true, 'message' => 'O usuário não tem autoridade para cancelar este pedido!'];
                    } else {
                        $item->requests()->detach($player_id);
                    }
                }
            }
        }
        return $response; 
    }
}
