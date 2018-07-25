<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rpg;

class RpgController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()) {
            return $request->user()->rpgs()->with('master')->get();
        } else {
            return Rpg::with('master')->get();
        }
    }

    public function show($id, Request $request)
    {
        if ($request->user()) {
            $rpg = $request->user()->rpgs()->with(['master', 'shops.items.players.user', 'players' => function ($query) {
                $query->with(['items', 'user']);
            }])->wherePivot('rpg_id', $id)->first();

            if ($rpg) {
                $rpg->player->load('user');
                return $rpg;
            }
        }
        return Rpg::with(['master', 'shops.items.players.user', 'players' => function ($query) {
            $query->with(['items', 'user']);
        }])->where('id', $id)->first();
    }

    public function register($id, Request $request) 
    {
        $response = ['error' => false, 'message' => 'Pedido de inscrição / desinscrição realizado com sucesso!'];
        if ($request->user()) {
            $rpg = Rpg::find($id);
            if ($rpg) {
                $credential = ($rpg->user_id === $request->user()->id)?4:$rpg->is_public;
                $request->user()->rpgs()->toggle([
                    $rpg->id => [
                        'credential' => $credential,
                        'gold' => $rpg->gold_starter,
                        'cash' => $rpg->cash_starter,
                        'detail' => '',
                        'image' => 'default.jpg',
                    ] 
                ]);
            }
        }
        return $response;
    } 
}
