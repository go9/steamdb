<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\Bundle;
use App\Game;


class BundleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('bundles.index')->withBundles(Bundle::paginate(10));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, array(
            'name'      =>'required|max:255',
            'store_id'      =>'required|numeric',
            'date_started'      =>'date|nullable',
            'date_ended'      =>'date|nullable',
        ));

        Session::flash('message-success', 'The bundle was successfully saved');
        $bundle = new Bundle($request->except(["_token"]));
        $bundle->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return view('bundles.bundle')->withBundle(Bundle::find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $bundle = Bundle::find($id);

        if($bundle == null){
            Session::flash("message-danger", "The bundle could not be located. Nothing was deleted.");
        }
        else{
            Session::flash("message-success", "The purchase was deleted");
            $bundle->delete();
        }

        return back();
    }

    public function linkGameToBundle(Request $request){
        $this->validate($request, array(
            'bundle_id'      =>'required',
            'game_ids'          =>'required',
            'tier'          =>'nullable|numeric',

        ));
        // Get the bundle
        $bundle = Bundle::find($request->bundle_id);
        if($bundle == null){
            return ["success" => false, "code" => 0, "message" => "Invalid Bundle ID"];
        }

        // Verify that the id's are in an array
        if(!is_array($request->game_ids)){
            $request->game_ids = [$request->game_ids];
        }

        // Add each game
        $results = [
            ["added" => null],
            ["skipped" => null]
        ];

        foreach($request->game_ids as $game_id){
            $game = Game::find($game_id);
            // Verify the game was found
            if($game == null){
                $results["skipped"][] = $game_id;
            }
            else{
                $bundle->games()->attach($game, ["tier" => $request->tier]);
                $results["added"][] = $game_id;
            }
        }

        return ["success" => true, "results" => $results];
    }

    public function removeGameFromBundle(Request $request){
        $this->validate($request, array(
            'bundle_id'      =>'required',
            'game_id'          =>'required',

        ));

        $bundle = Bundle::find($request->bundle_id);
        $game = Game::find($request->game_id);

        if($game == null || $bundle == null){
            // One of the ID's were bad.
            Session::flash('error', 'Invalid Input');
        }
        else{
            Session::flash('message-success', 'Successfully added removed from bundle');
            $bundle->games()->detach($game);
        }

        return back();
    }

    public function changeTier(Request $request){
        $this->validate($request, array(
            'bundle_game_id'      =>'numeric|required',
            'new_tier'       =>'numeric|min:0|required'
        ));

        if($request->new_tier == 0){
            Session::flash('message-success', 'Successfully removed bundle item');
            DB::table('bundle_game')->where('id', $request->bundle_game_id)->delete();
        }
        else{
            DB::table("bundle_game")->where("id",$request->bundle_game_id)->update(["tier" => $request->new_tier]);
        }

        return back();
    }
}
