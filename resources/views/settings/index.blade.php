@extends('main')
@section('title')

@section('content')
    <div class="row">

        <div class="col-xs-12 col-lg-3 col-xl-2">
            <ul class="list-group">
                <li class="list-group-item list-group-item-info">User Settings</li>
                <li class="list-group-item list-group-item-info" style="padding:5px;">
                    <div class="list-group " style="width:100%;">
                        <a href="/settings/myaccount" class="list-group-item list-group-item-action">My Account</a>
                        <a href="/settings/g2a" class="list-group-item list-group-item-action">G2A Settings</a>
                    </div>
                </li>
            </ul>
            @if(Auth::check() && Auth::user()->checkRole("admin"))
            <ul class="list-group" style="margin-top:10px;margin-bottom:10px;">
                <li class="list-group-item list-group-item-warning">Admin Settings</li>
                <li class="list-group-item list-group-item-warning" style="padding:5px;">
                    <div class="list-group" style="width:100%;">
                        <a href="#" class="list-group-item list-group-item-action">Manage Icons</a>
                        <a href="/settings/update_database_games" class="list-group-item list-group-item-action">Update Games</a>
                        <a href="/settings/update_database_packages" class="list-group-item list-group-item-action">Update Packages</a>
                        <a href="/settings/g2a_auto_matcher" class="list-group-item list-group-item-action">G2a Auto Matcher</a>
                        <a href="/settings/g2a_price_updater" class="list-group-item list-group-item-action">G2a Update Prices</a>
                    </div>
                </li>
            </ul>
            @endif
        </div>

        <div class="col-xs-12 col-lg-9 col-xl-10">
            @yield("settings-content")
        </div>
@endsection