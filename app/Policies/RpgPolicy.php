<?php

namespace App\Policies;

use App\User;
use App\Rpg;
use Illuminate\Auth\Access\HandlesAuthorization;

class RpgPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create rpgs.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the rpg.
     *
     * @param  \App\User  $user
     * @param  \App\Rpg  $rpg
     * @return mixed
     */
    public function update(User $user, Rpg $rpg)
    {
        $player = $rpg->players()->where('user_id', $user->id)->first();
        if ($player) {
            if ($player->credential > 2) {
                return true;
            }
        }
        return false;
    }

    /**
     * Determine whether the user can delete the rpg.
     *
     * @param  \App\User  $user
     * @param  \App\Rpg  $rpg
     * @return mixed
     */
    public function delete(User $user, Rpg $rpg)
    {
        //
    }

    public function buy(User $user, Rpg $rpg) 
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
