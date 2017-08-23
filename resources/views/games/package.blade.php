@extends('main')
@if(!$game)
    <div class="alert alert-danger">
        <strong>Error!</strong> Game not found
    </div>
@endif

@php
    $game->header_image = $game->images->where("type","header_image")->first()->url;
@endphp
@section('title', " | $game->name")

@section('content')
    <style>
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


         .card:hover .card-img {
             -webkit-filter: brightness(65%);
         }

    </style>

    <div class="container-fluid">
        <!-- Display Action button for users who are logged in -->
        @if(Auth::check())
            <div class="col-12">
                <div class="dropdown float-right">
                    <button class="btn btn-secondary dropdown-toggle"
                            style="width:100%;"
                            type="button"
                            id="dropdownMenuButton"
                            data-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false">
                        Actions
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" style="width:250px;"
                         aria-labelledby="dropdownMenuButton">

                        <style>
                            .table-divider {
                                background-color: rgba(1, 1, 1, .05);
                                text-align: center;
                                font-style: italic;
                                font-weight: bold;
                            }
                        </style>
                        <table class="table table-hover table-borderless">

                            <tr class="table-divider">
                                <td height="10" colspan="3">Catalogs</td>
                            </tr>
                            <tr class="table-divider">
                                <td colspan="3">Lists</td>
                            </tr>

                            <tr>

                                <td>Bundle</td>
                                <td>
                                    <button class='empty-button' type="button" data-toggle="modal"
                                            data-target="#bundle-modal">

                                    </button>
                                </td>
                            </tr>

                            <tr>

                                <td>Purchases</td>
                                <td>
                                    <button class='empty-button' type="button" data-toggle="modal"
                                            data-target="#purchase-modal">

                                    </button>
                                </td>
                            </tr>

                            <tr class="table-divider">
                                <td colspan="3">Tools</td>
                            </tr>

                        </table>
                    </div>
                </div>
            </div>
        @endif

        <div style="width:100%;clear:both;"></div>

        <!-- Set up container for description -->
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8" style="margin:0 auto;">
            <div class="row">

                <!-- title -->
                <div class="col-12" id="game-title" style="font-size:2em;margin-bottom:20px;">
                    {{$game->name}}
                </div>

                <hr>

                <div class="col-xs-12 col-lg-6" id="content-main" style="">

                    @php($counter = 0)
                    @foreach($game->movies as $movie)
                        @php($counter++)
                        <div style="display:none;" id="video-{{$counter}}">
                            <video class="lg-video-object lg-html5" controls preload="none">
                                <source src="{{$movie->url_hd}}" type="video/mp4">
                                Your browser does not support HTML5 video.
                            </video>
                        </div>
                    @endforeach

                    {{-- Display The games image--}}
                    <div id="game_image">
                        <ul id="lightSlider">
                            <li
                                    data-src="{{$game->header_image}}"
                                    data-thumb="{{$game->header_image}}">
                                <img class="img-responsive" style="width:100%;height:auto;"
                                     src="{{$game->header_image}}"/>
                            </li>
                            @php($counter = 0)
                            @foreach($game->movies as $movie)
                                @php($counter++)
                                <li
                                        data-poster="{{$movie->thumbnail}}"
                                        data-thumb="https://c3metrics.com/wp-content/uploads/2016/08/feature-video-thumbnail-overlay.png"
                                        data-html="#video-{{$counter}}">
                                    <img class="img-responsive" style="width:100%;height:auto;"
                                         src="{{$movie->thumbnail}}"/>
                                </li>
                            @endforeach
                            @foreach($game->screenshots as $screenshot)
                                <li
                                        data-src="{{$screenshot->url}}"
                                        data-thumb="{{$screenshot->thumbnail}}">
                                    <img class="img-responsive" style="width:100%;height:auto;"
                                         src="{{$screenshot->url}}"/>
                                </li>
                            @endforeach
                        </ul>

                        <script>
                            $(document).ready(function () {
                                $('#lightSlider').lightSlider({
                                    gallery: true,
                                    item: 1,
                                    loop: true,
                                    thumbItem: 9,
                                    slideMargin: 0,
                                    enableDrag: false,
                                    currentPagerPosition: 'left',
                                    onSliderLoad: function (el) {
                                        el.lightGallery({
                                            selector: '#lightSlider .lslide',
                                        });
                                    }
                                });
                            });

                        </script>

                    </div>


                </div> <!-- End content-main-->


                <div class="col-xs-12 col-lg-6" id="content-sidebar" style="margin-top:15px;">
                    <div style="background-image: url('https://media.licdn.com/media/AAEAAQAAAAAAAAiDAAAAJGE0YWI3YjMxLWVjMzYtNDBiMi05MjQxLTc0NWM4YjdjMGNkNA.png');
                                background-repeat:no-repeat;
                                background-size: 100%;
                                border:1px solid black;
                                padding:10px;
                                padding-top:50px;">

                    </div>
                </div>

                {{-- Print the games in the package --}}
                <div class="row" style="margin-top:15px;padding:30px;">
                    <h1>Package Contents:</h1>
                    <div style="width:100%;height:1px;"></div>
                    @foreach($game->packageContents as $content)
                        {{-- Get the package content indo --}}
                        @php($content = App\Game::find($content->content_id))

                        <div class="game card col-xs-12  col-sm-6 col-lg-4" style="padding:0px;">
                            <img class="card-img"
                                 style="width:100%;height:auto;"
                                 src="{{$content->images->where("type","header_image")->first()->url == null ? "http://cdn.akamai.steamstatic.com/steam/apps/10/header.jpg?t=1447887426" : $game->images->where("type","header_image")->first()->url}}"
                                 alt="Card image cap">
                            <div class="card-img-overlay">
                                <a href="/games/{{$content->id}}"
                                   class="card-title"
                                   style="background-color:rgba(1,1,1,.6);color:white;font-size:1.4em;padding:5px;">
                                    {!! $content->name !!}
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div> <!-- End content-sidebar-->

            <!-- End content -->
        </div>
    </div>
@endsection
