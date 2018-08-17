<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use App\Http\Requests\UpdateRpg;
use App\Http\Requests\CreateRpg;
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
        $response = ['error' => false, 'message' => 'Rpg carregado com sucesso!', 'data' => null];

        if ($request->user()) {
            $rpg = $request->user()->rpgs()->with(['master', 'shops.items.players.user', 'players' => function ($query) {
                $query->with(['items', 'user', 'requests']);
            }])->wherePivot('rpg_id', $id)->first();

            if ($rpg) {
                $rpg->player->load(['user', 'items', 'requests']);
                $response['data'] = $rpg;
                return $response;
            }
        }
        $rpg = Rpg::with(['master', 'shops.items.players.user', 'players' => function ($query) {
            $query->with(['items', 'user', 'requests']);
        }])->where('id', $id)->first();
        
        if ($rpg) {
            $response['data'] = $rpg;
            return $response;
        } else {
            $response['error'] = true;
            $response['message'] = 'Rpg não encontrado!';
            return $response;
        }
    }

    public function create(CreateRpg $request)
    {
        $response = ['error' => false, 'message' => 'Rpg criado com sucesso! Se inscreva na mesa que acabou de criar!!!'];
        $user = $request->user();
        if ($user->authority < 1) {
            $response = ['error' => true, 'message' => 'O usuário não tem autoridade para criar um rpg!'];
        } else {
            $count = Rpg::where('user_id', $user->id)->get()->count();
            if ($count > 2) {
                $response = ['error' => true, 'message' => 'O usuário alcançou o limite de rpgs que pode criar!'];
            } else {
                $user->my_rpgs()->create([
                    'name' => $request->name, 
                    'is_public' => $request->is_public,
                    'gold_starter' => $request->gold_starter,
                    'cash_starter' => $request->cash_starter,
                ]);
            }
        }
        return $response;
    }

    public function delete($id)
    {
        $response = ['error' => false, 'message' => 'Rpg deletado com sucesso!'];
        $rpg = Rpg::find($id);
        if (!$rpg) {
            $response['error'] = true;
            $response['message'] = 'Rpg não encontrado!';
        } else { 
            $this->authorize('delete', $rpg);
            $rpg->delete();
            $rpg->deleteDirectory();
        }
        return $response;
    }

    public function update(UpdateRpg $request) {
        $response = ['error' => false, 'message' => 'Rpg atualizado com sucesso!'];
        $rpg = Rpg::findOrFail($request->rpg_id);
        $this->authorize('update', $rpg);
        $rpg->update($request->only('name', 'gold_starter', 'cash_starter', 'is_public'));
        if ($request->has('image')) {
            $rpg->makeDirectory();
            //$rpg->deleteImage();
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
