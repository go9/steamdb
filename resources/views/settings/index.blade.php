@extends('main')
@section('title')

@section('content')
@section('actionbar-contents')
    <a class="dropdown-item" href="/settings/myaccount">My Account</a>
    @if(Auth::check() && Auth::user()->checkRole("admin"))
        <div class="dropdown-divider"></div>
        <h6 class="dropdown-header">Admin Actions</h6>
        <a href="/settings/edit_icons"
           class="dropdown-item">Manage Icons</a>
        <a href="/settings/update_database_games"
           class="dropdown-item">Update Games</a>
        <a href="/settings/update_database_packages"
           class="dropdown-item">Update Packages</a>
        <a href="/settings/g2a_auto_matcher"
           class="dropdown-item">G2aAuto Matcher</a>
        <a href="/settings/g2a_price_updater"
           class="dropdown-item">G2aUpdate Prices</a>

    @endif

    <script>$("#action-bar").show()</script>
@endsection


    @yield("settings-content")


@endsection