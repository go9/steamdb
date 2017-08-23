<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Metacritic extends Model
{
  public function games() {
    return $this->belongsTo('App\Game');
  }
}
