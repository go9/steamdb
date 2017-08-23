<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

use App\Role;

class User extends Authenticatable
{
    use Notifiable;

    public $catalogs = [
        "scanned" => false,
        "inventory" => [],
        "library" => []
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function purchases()
    {
        return $this->hasMany('App\Purchase');
    }

    // Custom

    public function library()
    {
        if ($this->catalogs["scanned"] == false) {
            $this->catalogs();
        }

        return Game::whereIn("id", $this->catalogs["library"]);
    }

    public function inventory()
    {
        if ($this->catalogs["scanned"] == false) {
            $this->catalogs();
        }
        return Game::whereIn("id", $this->catalogs["inventory"]);
    }

    public function isIn($game_id, $catalog)
    {
        // Verify that catalogs was scanned
        if ($this->catalogs["scanned"] == false) {
            $this->catalogs();
        }

        if (sizeof($this->catalogs[$catalog]) > 0 && in_array($game_id, $this->catalogs()[$catalog])) {
            return true;
        } else {
            return false;
        }
    }

    public function catalogs()
    {
        // Check if we scanned already
        if ($this->catalogs["scanned"] == false) {
            // Get all the purchases
            foreach ($this->purchases as $purchase) {
                // Go through the purchases' items
                foreach ($purchase->games as $game) {
                    // Check if the game is available
                    if ($game->pivot->availability == -1) {
                        if (!in_array($game->id, $this->catalogs["library"])) {
                            $this->catalogs["library"][] = $game->id;
                        }
                    }
                    if ($game->pivot->availability == 0) {
                        if (!in_array($game->id, $this->catalogs["inventory"])) {
                            $this->catalogs["inventory"][] = $game->id;
                        }
                    }
                }
            }
            // Set scanned to true
            $this->catalogs["scanned"] = true;
        }

        return $this->catalogs;
    }

    public function roles()
    {
        return $this->belongsToMany('App\Role');
    }

    public function checkRole($role){
        return $this->roles()->where("name",$role)->exists() ? true : false;
    }

    public function attachRole($role){
        $this->roles()->attach(Role::where("name", $role)->first());
        return $this;
    }

    public function detachRole($role){
        $this->roles()->detach(Role::where("name", $role)->first());
        return $this;
    }

}
