@extends('main')

@section('title')

@section('content')

        @include("games.games_printer", ["games" => $games, "settings" => ["print_advanced" => true]])

        @if(count($games) == 0)
            <div class="alert alert-warning">
                Your search did  not return any matches. <br>
                <br>
                Suggestions:
                <ul>
                    <li>Make sure all words are spelled correctly.</li>
                    <li>Try different keywords.</li>
                    <li>Try more general keywords.</li>
                    <li>Try fewer keywords.</li>
                    <li>Remove filters</li>
                </ul>

            </div>

        @endif
@endsection
