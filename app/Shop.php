<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Shop extends Model
{
    protected $guarded = ['id'];

    public function deleteDirectory()
    {
        if (Storage::exists('images/rpgs/'.$this->rpg->id.'/shops/'.$this->id)) {
            Storage::deleteDirectory('images/rpgs/'.$this->rpg->id.'/shops/'.$this->id);
        }
    }

    public function items()
    {
        return $this->hasMany('App\Item')
                    ->orderBy('name', 'asc');
    }

    public function rpg()
    {
        return $this->belongsTo('App\Rpg');
    }
}
