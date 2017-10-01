<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Icon;

class IconController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            "id" => "required",
            "name" => "required",
            "default_color" => "nullable",
            "default_size" => "nullable",
        ]);

        $icon = new Icon();

        $icon->id = $request->id;

        if(isset($request->name) && $request->name){
            $icon->name = ucfirst($request->name);
        }
        else{
            $icon->name = ucfirst($request->id);
        }

        if(isset($request->default_color) && $request->default_color){
            $icon->default_color = $request->default_color;
        }

        if(isset($request->default_size) && $request->default_size){
            $icon->default_size = $request->default_size;
        }

        $icon->save();

        Session::flash("message-success", "Icon updated");
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
        //
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
    public function update(Request $request)
    {
        $this->validate($request, [
            "old_id" => "required|string",
            "id" => "nullable|string",
            "name" => "nullable|string",
            "default_color" => "nullable|string",
            "default_size" => "nullable|string",
        ]);

        if(($icon = Icon::find($request->old_id)) == null){
            Session::flash("message-danger", "Icon not found.");
            return back();
        }

        if(isset($request->id) && $request->id){
            $icon->id = $request->id;
        }

        if(isset($request->name) && $request->name){
            $icon->name = $request->name;
        }

        if(isset($request->default_color) && $request->default_color){
            $icon->default_color = $request->default_color;
        }

        if(isset($request->default_size) && $request->default_size){
            $icon->default_size = $request->default_size == "default" ? "" : $request->default_size;
        }

        $icon->save();

        Session::flash("message-success", "Icon updated");
        return back();

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
