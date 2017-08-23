<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
  public function games() {
    return $this->belongsTo('App\Game');
  }

  public function themes(){
      return $this->hasOne('App\ImageTheme');
  }
}
