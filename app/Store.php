<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    public $timestamps = false;

    public function games(){
        return $this->belongsToMany('App\Game')->withPivot("price")->withTimestamps();
    }

}
