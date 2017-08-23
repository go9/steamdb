<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Screenshot extends Model
{
    protected $fillable = ['game_id', 'url', 'thumbnail'];

    public function games()
    {
        return $this->belongsTo('App\Game');
    }
}
