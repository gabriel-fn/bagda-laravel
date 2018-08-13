<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRpg;
use App\Rpg;
use App\Player;

class RpgController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return $request->user()->rpgs()->with('master')->orderBy('name', 'asc')->get();
        } else {
            return Rpg::with('master')->orderBy('name', 'asc')->get();
        }
    }

    public function show($id, Request $request)
    {
        if ($request->user()) {
            $rpg = $request->user()->rpgs()->with(['master', 'shops.items.players.user', 'players' => function ($query) {
                $query->with(['items', 'user', 'requests']);
            }])->wherePivot('rpg_id', $id)->first();

            if ($rpg) {
                $rpg->player->load(['user', 'items', 'requests']);
                return $rpg;
            }
        }
        return Rpg::with(['master', 'shops.items.players.user', 'players' => function ($query) {
            $query->with(['items', 'user', 'requests']);
        }])->where('id', $id)->first();
    }

    public function update(UpdateRpg $request) {
        $response = ['error' => false, 'message' => 'Rpg atualizado com sucesso!'];
        $rpg = Rpg::findOrFail($request->rpg_id);
        $this->authorize('update', $rpg);
        $rpg->update($request->only('name', 'gold_starter', 'cash_starter', 'is_public'));
        if ($request->has('image')) {
            $rpg->makeDirectory();
            $request->file('image')->storeAs('images/rpgs/'.$rpg->id, $rpg->id.'.jpg');
        }
        return $response;
    }

    public function register($id, Request $request) 
    {
        $response = ['error' => false, 'message' => 'Pedido de inscrição / desinscrição realizado com sucesso!'];
        $rpg = Rpg::find($id);
        if (!$rpg) {
            $response['error'] = true;
            $response['message'] = 'Rpg não encontrado!';
        } else {    
            $player = $request->user()->players()->where('rpg_id', $rpg->id)->first();
            if ($player) {
                $player->deleteImage();
            }

            $credential = ($rpg->user_id === $request->user()->id)?4:$rpg->is_public;
            $request->user()->rpgs()->toggle([
                $rpg->id => [
                    'credential' => $credential,
                    'gold' => $rpg->gold_starter,
                    'cash' => $rpg->cash_starter,
                    'detail' => '',
                ] 
            ]);
        }
        return $response;
    } 

    public function registerResponse(Request $request) 
    {
        $response = ['error' => false, 'message' => 'Pedido de inscrição avaliado com sucesso!'];
        $player = Player::find($request->player_id);
        if (!$player) {
            $response['error'] = true;
            $response['message'] = 'Jogador não encontrado!';
        } else {    
            $this->authorize('update', $player);
            if ($request->accept) {
                $player->credential = 1;
                $player->save();
            } else {
                $player->delete();
                $player->deleteImage();
            }
        }
        return $response;
    }
}
