<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class G2a extends Model
{
    public function game()
    {
        return $this->belongsTo('App\Game', 'g2a_id', 'id');
    }
}
