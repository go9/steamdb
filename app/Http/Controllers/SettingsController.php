<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use App\User;
use App\Game;

class SettingsController extends Controller
{
    public function index(){
        if (!Auth::check()) {
            Session::flash("message-info", "You must be logged in to use this feature");
            return Redirect::route("login");
        }

        return view("settings.index");
    }

    public function showConnections(){
        return view("settings.connections");
    }

    public function showMyAccount(){
        return view("settings.myaccount");
    }

    public function toggleG2a(Request $request){
        $this->validate($request, [
            "user_id" => "numeric"
        ]);

        // Get user and verify they exist
        $user = User::find($request->user_id);
        if($user == null){
            Session::flash("message-danger", "User not found.");
        }

        // Toggle
        if($user->checkRole("G2a")){
            // They have the role, get rid of it
            Session::flash("message-success", "G2a has been removed from your account.");
            $user->detachRole("G2a");
        }
        else{
            // They don't have the role, add it
            Session::flash("message-success", "G2a has been added to your account.");
            $user->attachRole("G2a");
        }

        return back();
    }


    public function g2aPriceUpdater(){
        return view("settings.g2a_price_updater")->withGames(Game::whereNotNull("g2a_id")->where("public",1)->get());
    }
}
