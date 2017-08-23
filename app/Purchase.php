<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = ['user_id','store_id','name','price_paid','date_purchased','tier_purchased','bundle_id','notes'];

    public function games(){
        return $this->belongsToMany('App\Game')->withPivot("id", "availability", "price_paid", "price_sold","key","notes");
    }

    public function user(){
        return $this->belongsTo('App\User');
    }

    public function store(){
        return $this->belongsTo('App\Store');
    }
}
