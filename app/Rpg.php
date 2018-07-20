<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rpg extends Model
{
    protected $guarded = ['id'];

    public function shops()
    {
        return $this->hasMany('App\Shop');
    }

    public function items()
    {
        return $this->hasManyThrough('App\Item', 'App\Shop');
    }

    public function master() 
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function players() {
        return $this->hasMany('App\Player');
    }

    public function users() 
    {
        return $this->belongsToMany('App\User', 'players')
                    ->withTimeStamps()
                    ->as('players')
                    ->withPivot('credential', 'gold', 'cash', 'detail', 'id')
                    ->using('App\Player');
    }
}
