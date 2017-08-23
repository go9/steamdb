<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImageTheme extends Model
{
  public function images(){
    return $this->hasMany('App\Image');
  }
}
