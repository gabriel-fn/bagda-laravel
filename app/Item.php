<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $guarded = ['id'];

    public function players() 
    {
        return $this->belongsToMany('App\Player', 'item_player', 'item_id', 'player_id')
                    ->as('process')
                    ->withPivot('status');
    }
    
}
