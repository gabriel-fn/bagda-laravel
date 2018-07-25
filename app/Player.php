<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Player extends Pivot
{
    protected $table = "players";

    protected $guarded = ['id'];

    public function user() 
    {
        return $this->belongsTo('App\User');
    }

    public function rpg() 
    {
        return $this->belongsTo('App\Rpg');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item', 'item_player', 'player_id', 'item_id')
                    ->as('process')
                    ->withPivot(['units']);
    }

    public function requests()
    {
        return $this->belongsToMany('App\Item', 'item_request', 'player_id', 'item_id');
    }
}
