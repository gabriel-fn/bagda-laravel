<?php

namespace App\Policies;

use App\User;
use App\Player;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlayerPolicy
{
    use HandlesAuthorization;

    public function inventary(User $user, Player $player) 
    {
        if ($user && $player) {
            $user_player = $user->rpgs()->wherePivot('rpg_id', $player->rpg_id)->first();
            if ($user_player) {
                if ($user_player->player->id === $player->id || $user_player->player->credential > 1) {
                    return true;
                }
            }
        }
        return false;
    }
}
