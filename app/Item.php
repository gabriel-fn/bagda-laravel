<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Item extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['image'];

    public function deleteImage()
    {
        if (Storage::exists('images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id.'/'.$this->id.'.jpg')) {
            Storage::delete('images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id.'/'.$this->id.'.jpg');
        }
    }

    public function makeDirectory()
    {
        if (!Storage::exists('images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id)) {
            Storage::makeDirectory('images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id);
        }
    }

    public function getImageAttribute() {
        if (!Storage::exists('images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id.'/'.$this->id.'.jpg')) {
            return asset('storage/images/default.jpg');
        }
        return asset('storage/images/rpgs/'.$this->shop->rpg->id.'/shops/'.$this->shop->id.'/'.$this->id.'.jpg');
    }

    public function shop()
    {
        return $this->belongsTo('App\Shop');
    }

    public function players() 
    {
        return $this->belongsToMany('App\Player', 'item_player', 'item_id', 'player_id')
                    ->withTimestamps()
                    ->as('process')
                    ->withPivot(['units']);
    }

    public function requests() 
    {
        return $this->belongsToMany('App\Player', 'item_request', 'item_id', 'player_id')
                    ->withTimestamps()
                    ->as('process');
    }
}
