<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rpg extends Model
{
    protected $guarded = ['id'];

    public function quests()
    {
        return $this->hasMany('App\Quest');
    }

    public function reports()
    {
        return $this->hasMany('App\Report');
    }

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
        return $this->belongsTo('App\User');
    }

    public function players() 
    {
        return $this->belongsToMany('App\User')
                    ->as('player')
                    ->withPivot('credential', 'gold', 'cash', 'detail');
    }
}
