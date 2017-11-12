<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    public function messages(){
        return $this->hasMany("App\Message");
    }

    public function users(){
        return $this->belongsToMany("App\User");
    }

    public function getMostRecentMessageTimestampAttribute(){
        return $this->messages()->orderBy("created_at", 'desc')->pluck("created_at")->first();
    }
}
