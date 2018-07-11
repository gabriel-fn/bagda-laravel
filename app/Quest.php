<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quest extends Model
{
    protected $guarded = ['id'];

    public function admin()
    {
        return $this->belongsTo('App\User');
    }

    public function players()
    {
        return $this->belongsToMany('App\User');
    }

    public function items()
    {
        return $this->belongsToMany('App\Item');
    }
}
