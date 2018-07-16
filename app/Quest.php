<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $guarded = ['id'];

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function players()
    {
        return $this->belongsToMany('App\User');
    }

    public function rpg()
    {
        return $this->belongsTo('App\Rpg');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
}
