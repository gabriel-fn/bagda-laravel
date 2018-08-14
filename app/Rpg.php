<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Rpg extends Model
{
    protected $guarded = ['id'];

    protected $appends = ['image'];

    public function makeDirectory()
    {
        if (!Storage::exists('images/rpgs/'.$this->id)) {
            Storage::makeDirectory('images/rpgs/'.$this->id);
        }
    }

    public function deleteDirectory()
    {
        if (Storage::exists('images/rpgs/'.$this->id)) {
            Storage::deleteDirectory('images/rpgs/'.$this->id);
        }
    }

    public function getImageAttribute() {
        if (!Storage::exists('images/rpgs/'.$this->id.'/'.$this->id.'.jpg')) {
            return asset('storage/images/rpgs/default.jpg');
        }
        return asset('storage/images/rpgs/'.$this->id.'/'.$this->id.'.jpg');
    }

    public function shops()
    {
        return $this->hasMany('App\Shop')
                    ->orderBy('name', 'asc');
    }

    public function items()
    {
        return $this->hasManyThrough('App\Item', 'App\Shop')
                    ->orderBy('name', 'asc');
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
                    ->as('player')
                    ->withPivot('credential', 'gold', 'cash', 'detail', 'id')
                    ->using('App\Player');
    }
}
