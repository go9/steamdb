<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    public $fillable = ["steam_id", "type"];

    // Relationships
    public function metacritics()
    {
        return $this->hasOne('App\Metacritic');
    }

    public function g2a()
    {
        return $this->hasOne('App\G2a', 'id', 'g2a_id');
    }

    public function bundles()
    {
        return $this->belongsToMany('App\Bundle')->withPivot("tier", "id");
    }

    public function prices()
    {
        return $this->belongsToMany('App\Store', 'game_store')->withPivot("price")->withTimestamps();
    }

    public function purchases()
    {
        return $this->belongsToMany('App\Purchase');
    }

    public function languages()
    {
        return $this->belongsToMany('App\Language');
    }

    public function developers()
    {
        return $this->belongsToMany('App\Developer');
    }

    public function publishers()
    {
        return $this->belongsToMany('App\Publisher');
    }

    public function categories()
    {
        return $this->belongsToMany('App\Category');
    }

    public function genres()
    {
        return $this->belongsToMany('App\Genre');
    }

    public function movies()
    {
        return $this->hasMany('App\Movie');
    }

    public function screenshots()
    {
        return $this->hasMany('App\Screenshot');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function headerImage(){
        $headerImage = $this->images->where("type","header_image")->first();
        if($headerImage == null){
            $headerImage = new Image();
            $headerImage->url = "../images/empty_banner.gif";
        }
        return $headerImage;
    }

    public function packageContents()
    {
        return $this->hasMany('App\PackageContent', 'package_id');
    }


}
