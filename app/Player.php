<?php

namespace App;

//use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Storage;

class Player extends Pivot
{
    protected $table = "players";

    protected $guarded = ['id'];

    protected $appends = ['image', 'name', 'request_number'];

    public function getImageAttribute() {
        if (!Storage::exists('images/players/'.$this->id.'.jpg')) {
            return asset('storage/images/players/default.jpg');
        }
        return asset('storage/images/players/'.$this->id.'.jpg');
    }

    public function getNameAttribute() {
        return $this->user->name;
    }

    public function getRequestNumberAttribute() {
        return $this->requests->count();
    }

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
                    ->withTimestamps()
                    ->as('process')
                    ->withPivot(['units'])
                    ->orderBy('name', 'asc');
    }

    public function requests()
    {
        return $this->belongsToMany('App\Item', 'item_request', 'player_id', 'item_id')
                    ->withTimestamps()
                    ->as('process');
    }
}
