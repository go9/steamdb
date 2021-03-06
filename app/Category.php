<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'id'];
    public $timestamps = false;

    public function games()
    {
        return $this->belongsToMany('App\Game');
    }
}
