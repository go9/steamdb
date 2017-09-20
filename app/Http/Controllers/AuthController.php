<?php

namespace App\Http\Controllers;

use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Session;
use Invisnik\LaravelSteamAuth\SteamAuth;
use App\User;
use Auth;

class AuthController extends Controller
{
    /**
     * The SteamAuth instance.
     *
     * @var SteamAuth
     */
    protected $steam;

    /**
     * The redirect URL.
     *
     * @var string
     */
    protected $redirectURL = '/';

    /**
     * AuthController constructor.
     *
     * @param SteamAuth $steam
     */
    public function __construct(SteamAuth $steam)
    {
        $this->steam = $steam;
    }

    /**
     * Redirect the user to the authentication page
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectToSteam()
    {
        return $this->steam->redirect();
    }

    /**
     * Get user info and log in
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function handle()
    {
        // Check if the user just validated their info
        if ($this->steam->validate()) {

            // Get the info
            $info = $this->steam->getUserInfo();


            if($info){
                // Check if steam is account is already linked to local account
                if($user = User::where('steamid', $info->steamID64)->first()){

                    // Check if the user is currently logged in and the accounts match
                    if(Auth::check()){
                        if($user->id != Auth::user()->id){
                            Session::flash("message-danger", "Another user is already synced with this Steam account.");
                            return redirect("settings/connections");
                        }
                    }

                    // The user is trying to log in
                    Auth::login($user);
                    return redirect($this->redirectURL); // redirect to site
                }
                else{
                    // the user is signing up
                    // Check if the user is currently logged in
                    if(Auth::check()){
                        // the user is syncing their steam account to their local account
                        $user = Auth::user();
                        $user->steamid = $info->steamID64;
                        $user->avatar = $info->avatar;
                        $user->save();

                        return redirect("settings/connections");
                    }
                    else{
                        return view("auth.register")->withInfo($info);
                    }

                }
            }
        }
        return $this->redirectToSteam();
    }


}