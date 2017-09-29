@extends('main')

@section('title')

@section('content')
    @include("games.games_printer", ["games" => $games, "settings" => ["print_advanced" => true]])
@endsection
