@extends('main')

@section('title')

@section('content')

    <!-- Custom Page Styling -->
    <style>
        .card:hover .card-img {
            -webkit-filter: brightness(65%);
        }
        .store-game {
            padding: 10px;
        }

        .store-game:hover {
            box-shadow: 0px 0px 2px .5px #67c1f5;
            background-color: rgba(103, 193, 245, .1);
        }

        #game-title {
            font-size: 1.2em;
            width: 100%;
            white-space: nowrap;
            text-overflow: ellipsis;
            display: block;
            overflow: hidden;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
        }

        .btn-group-button:hover {
            background-color: lightslategrey;
        }

        .btn-group-button:focus {
            outline: 0;
            background-color: slategray;
        }
    </style>


    @include("games.games_printer", ["games" => $games, "settings" => ["print_advanced" => true]])





@endsection
