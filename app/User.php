<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function rpgs() 
    {
        return $this->belongsToMany('App\Rpg', 'players')
                    ->withTimeStamps()
                    ->as('player')
                    ->withPivot('credential', 'gold', 'cash', 'detail', 'id', 'image')
                    ->using('App\Player');
    }

    public function players() {
        return $this->hasMany('App\Player');
    }

    public function my_rpgs()
    {
        return $this->hasMany('App\Rpg');
    }
}
