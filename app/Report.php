<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $guarded = ['id'];

    public function rpg() 
    {
        return $this->belongsTo('App\Rpg');
    }
}
