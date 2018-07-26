<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['image'];

    public function getImageAttribute() {
        if (!Storage::exists('images/items/'.$this->id.'.jpg')) {
            return asset('storage/images/items/default.jpg');
        }
        return asset('storage/images/items/'.$this->id.'.jpg');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function players() 
    {
        return $this->belongsToMany('App\Player', 'item_player', 'item_id', 'player_id')
                    ->as('process')
                    ->withPivot(['units']);
    }

    public function requests() 
    {
        return $this->belongsToMany('App\Player', 'item_request', 'item_id', 'player_id');
    }
}
