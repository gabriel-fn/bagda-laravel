<?php

namespace App\Policies;

use App\User;
use App\Rpg;
use Illuminate\Auth\Access\HandlesAuthorization;

class RpgPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the rpg.
     *
     * @param  \App\User  $user
     * @param  \App\Rpg  $rpg
     * @return mixed
     */
    public function view(User $user, Rpg $rpg)
    {
        //
    }

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
}
