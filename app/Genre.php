<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $fillable = ['name', 'id'];
    public $timestamps = false;

    public function games()
    {
        return $this->belongsToMany('App\Game');
    }
}
