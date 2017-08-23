<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackageContent extends Model
{
    public $fillable = ["package_id","content_id"];

    public function games()
    {

        return $this->belongsTo('App\Game', 'game_id', 'package_id');
    }
}
