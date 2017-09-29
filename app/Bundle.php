<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bundle extends Model
{
    protected $fillable = ["store_id", "name", "date_started", "date_ended"];

    public function games(){
        return $this->belongsToMany('App\Game')->withPivot("tier", "id");
    }

    public function stores(){
        return $this->belongsTo('App\Store', "store_id", "id");
    }

    public function tiers(){
        $gamesByTier = [];
        foreach($this->games as $bundleItem){
            $gamesByTier[$bundleItem->pivot->tier][] = $bundleItem;
        }

        ksort($gamesByTier);
        return $gamesByTier;
    }
}
