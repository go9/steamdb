@extends('main')
@section('title')

@section('content')

    <style>

        #app {
            height: 500px;
        }


        #messenger-messages {
            width: 100%;
            height: 100%;
            background-color: white;
            display: flex;
            flex-direction: column;
        }

        #messenger-toolbar {
            position: absolute;
            width: 100%;
            height: auto;
            z-index: 99;
        }

        #messenger-users {
            padding-top: 50px;
            width: 90%;
            max-width:500px;
            height: 50%;
            overflow-y: scroll;
            position: absolute;
            background-color: lightgray;
            transition: all 1s;
        }

        .hidden {
            display: none;
            transition: all 1s;
        }

        /*OLD*/

        .composer-container {
            height: 100%;
            display: flex;
        }

        #messenger-container {
            width: 100%;
            height: 100%;
            margin: 0 auto;
            display: flex;
        }

    </style>


    <div id="app">
        <div id="messenger-toolbar">
            <button class="btn btn-empty" onclick="$('#messenger-users').toggleClass('hidden')">
                <i class="fa fa-columns" aria-hidden="true"></i>
            </button>
        </div>

        <messenger-users :conversations="conversations"></messenger-users>

        <messenger-messages :auth="auth"></messenger-messages>

    </div>

    <div style="width:100%;clear:both;"></div>

    <script src="/js/app.js" charset="utf-8"></script>


@endsection