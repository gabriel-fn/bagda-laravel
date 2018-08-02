<?php

namespace App\Policies;

use App\User;
use App\Rpg;
use Illuminate\Auth\Access\HandlesAuthorization;

class RpgPolicy
{
    use HandlesAuthorization;

    public function update(User $user, Rpg $rpg)
    {
        if ($user && $rpg) {
            $player = $rpg->players()->where('user_id', $user->id)->first();
            if ($player) {
                if ($player->credential > 2) {
                    return true;
                }
            }
        }
        return false;
    }

    public function player(User $user, Rpg $rpg) 
    {
        if ($user && $rpg) {
            $rpg_with_player = $user->rpgs()->wherePivot('rpg_id', $rpg->id)->first();
            if ($rpg_with_player) {
                if ($rpg_with_player->player->credential > 0) {
                    return true;
                }
            }
        }
        return false;
    }
}
