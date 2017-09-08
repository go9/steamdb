@extends('main')

@section('content')
    <!-- Check if this game is missing -->
    @if($game == null)
        <div class="alert alert-danger">
            <strong>Error!</strong> Game not found
        </div>
    @else
@section('title', " | $game->name")
<div class="container-fluid">
    <!-- Display Action button for users who are logged in -->
    <div style="width:100%;clear:both;"></div>

    <!-- Set up main container -->
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-10 col-xl-8" style="margin:0 auto;">
        <div class="row">
            <!-- title -->
            <div class="col-12" id="game-title" style="font-size:2em;margin-bottom:20px;">
                @if(Auth::user())
                    @if(Auth::user()->isIn($game->id, "inventory"))
                        {!! App\Icon::find("archive")->wrap() !!}
                    @endif

                    @if(Auth::user()->isIn($game->id, "library"))
                        {!! App\Icon::where("name","Library")->first()->wrap() !!}
                    @endif
                @endif
                {{$game->name}}
            </div>
            <hr>
            <!-- set up content top panel-->
            <div class="col-xs-12 col-lg-6" id="content-main" style="">

                @php($counter = 0)
                @foreach($game->movies as $movie)
                    @php($counter++)
                    <div style="display:none;" id="video-{{$counter}}">
                        <video class="lg-video-object lg-html5" controls preload="none">
                            <source src="{{$movie->webm_hd}}" type="video/mp4">
                            Your browser does not support HTML5 video.
                        </video>
                    </div>
                @endforeach

                {{-- Display The games image--}}
                <div id="game_image" style="margin-top:15px;">
                    <ul id="lightSlider">
                        <li
                                data-src="{{$game->headerImage()->url}}"
                                data-thumb="{{$game->headerImage()->url}}">
                            <img class="img-responsive" style="width:100%;height:auto;"
                                 src="{{$game->headerImage()->url}}"/>
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

                <div style="margin-top:15px;">

                    {{-- Display The games description--}}
                    <div id="game_description"
                         style="background:aliceblue;margin-bottom:10px;padding:15px;overflow:hidden;display:none;">
                        {!! strip_tags($game->description,"<br><strong>") !!}
                    </div>
                    <button class="btn btn-secondary"
                            style="width:100%;"
                            onclick="$('#game_description').fadeToggle('fast')">
                        Toggle description
                    </button>
                </div>
            </div> <!-- End content-main-->
            <!-- set up sidebar panel-->
            <div class="col-xs-12 col-lg-6" id="content-sidebar" style="margin-top:15px;">
            @if(Auth::check() && Auth::user()->checkRole("G2a"))
                <!-- Display G2A Pricing-->
                    <div>
                        @php
                            $data = ["dates" => [], "prices" => [], "stores" => []];
                            foreach($game->prices->sortByDesc("created_at") as $result){
                                if($result->pivot->store_id ==  resolve("App\External\G2aApi")->getStore()->id){
                                    $data["dates"][] = strtotime($result->pivot->created_at);
                                    $data["prices"][] = (float)$result->pivot->price;
                                    $data["stores"][] = $result->pivot->store_id;
                                }
                            }

                            $g2a = $game->g2a;
                        @endphp

                        @if(sizeof($data["prices"]) > 0)
                            <div style="border:1px solid black;padding:10px;">
                            @if($g2a != null)


                                <div class="row">
                                    <div class="col-12" style="text-align: center;">
                                        <a href="http://www.g2a.com{{$g2a->slug}}"
                                           target="_blank"
                                           >
                                            {!! $g2a->name !!}
                                        </a>
                                    </div>

                                    <div class="col-12" style="text-align: center;">
                                    <span style="font-size: 2em;float:right;"
                                          data-toggle="tooltip"
                                          title="{!! \Carbon\Carbon::createFromTimeStamp(end($data["dates"]))->diffForHumans()!!}">
                                            {{money(end($data["prices"]))}}
                                        </span>
                                    </div>
                                    {{--
                                    <div class="col-2">
                                        <a href="http://www.g2a.com{{$g2a->slug}}"
                                           target="_blank"
                                           data-toggle="tooltip"
                                           title="{!! $g2a->name !!}">
                                            <img style="width:auto;height:58px;" src="{{$g2a->thumbnail}}">
                                        </a>
                                    </div>
                                    --}}
                                </div>

                                <hr>

                                <div id="myChart" style="clear:both;padding:1px;width:100%;"></div>
                                <div style="clear:both"></div>

                                <script>
                                    var data = {!! json_encode($data) !!};

                                    // Build array of data for chart
                                    var chartData = [];
                                    for (var i = 0; i < data.prices.length; i++) {
                                        console.log("Adding: date->" + new Date(data.dates[i] * 1000) + " prices->" + data.prices[i]);
                                        chartData.push(
                                            [
                                                new Date(data.dates[i] * 1000),
                                                data.prices[i]
                                            ]);
                                    }
                                    google.charts.load('current', {'packages': ['annotationchart']});
                                    google.charts.setOnLoadCallback(drawChart);

                                    function drawChart() {
                                        var data = new google.visualization.DataTable();
                                        data.addColumn('date', 'Date');
                                        data.addColumn('number', 'G2A Price');
                                        //data.addColumn('number', 'Steam Price');
                                        data.addRows(chartData);
                                        var chart = new google.visualization.AnnotationChart(document.getElementById('myChart'));

                                        var options = {
                                            displayAnnotations: true
                                        };

                                        chart.draw(data, options);
                                    }

                                </script>
                            @else
                                <div class="alert alert-info">
                                    <strong>No G2A Match</strong> Manually adding games is currently not available.
                                </div>
                            @endif
                        </div>
                        @endif
                    </div>
                @endif
            </div>
            <!-- set up content bottom panel-->
            <div class="col-xs-12 col-lg-6" id="content-main" style="">
                {{-- Display The games steam info--}}
                <div id="game_info" style="margin-top:15px;">
                    <table id="game_info_table" class="table" style="width:100%;text-overflow:ellipsis;">

                        {{-- Check if metacritic score exists--}}
                        @if($game->metacritics)
                            <tr>
                                <td><strong>Metacritic</strong></td>
                                {{-- Check if metacritic url exists--}}
                                <td>{{$game->metacritics->score}}
                                    @if($game->metacritics->url)
                                        <a href="{{$game->metacritics->url}}" target="_blank">
                                            {!! App\Icon::find("external-link-square")->wrap() !!}
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        {{-- Check if metacritic score exists--}}
                        @if($game->release_date)
                            <tr>
                                <td><strong>Release Date</strong></td>
                                <td>
                                    @php($date = \Carbon\Carbon::createFromTimeStamp(strtotime($game->release_date)))
                                    {{ $date->toFormattedDateString() }}
                                    ({{ $date->diffForHumans() }})
                                </td>
                            </tr>
                        @endif

                        {{-- Check if publishers exists--}}
                        @if($game->Publishers)

                            <tr>
                                <td><strong>Publishers</strong></td>
                                <td>
                                    @foreach($game->Publishers as $publisher)
                                        <a href="/games?publishers[]={{$publisher->id}}"> {{$publisher->name}}</a>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>

                        @endif
                        {{-- Check if Categories exists--}}
                        @if($game->Categories)
                            <tr>
                                <td><strong>Categories</strong></td>
                                <td>
                                    @foreach($game->Categories as $cat)
                                        <a href="/games?categories[]={{$cat->id}}"> {{$cat->name}} </a> <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        {{-- Check if Genres exists--}}
                        @if($game->genres)
                            <tr>
                                <td><strong>Genres</strong></td>
                                <td>
                                    @foreach($game->Genres as $genre)
                                        <a href="/games?genres[]={{$genre->id}}"> {{$genre->name}} </a> <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        {{-- Check if Developers exists--}}
                        @if($game->developers)
                            <tr>
                                <td><strong>Developers</strong></td>
                                <td>
                                    @foreach($game->Developers as $developer)
                                        <a href="/games?developers[]={{$developer->id}}"> {{$developer->name}} </a>
                                        <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        @if($game->languages)
                            <tr>
                                <td><strong>Languages</strong></td>
                                <td>
                                    @foreach($game->languages as $lang)
                                        <a href="/games?languages[]={{$lang->id}}"> {{$lang->name}} </a> <br>
                                    @endforeach
                                </td>
                            </tr>
                        @endif
                        {{-- Check if website exists--}}
                        @if($game->website)
                            <tr>
                                <td><strong>Website</strong></td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" style="padding:2px;font-size:.7em;"
                                               value="{{$game->website}}">
                                        <a class="input-group-addon"
                                           style="padding:2px;border:none;text-decoration:none;font-size:1.3em;"
                                           href="{{$game->website}}"
                                           target="_blank">
                                            {!! App\Icon::find("external-link-square")->wrap() !!}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                        {{-- Check if support url exists--}}
                        @if($game->support_url)
                            <tr style="text-overflow:ellipsis;">
                                <td><strong>Support</strong></td>
                                <td>
                                    <div class="input-group">
                                        <input type="text" class="form-control" style="padding:2px;font-size:.7em;"
                                               value="{{$game->support_url}}">
                                        <a class="input-group-addon"
                                           style="padding:2px;border:none;text-decoration:none;font-size:1.3em;"
                                           href="{{$game->support_url}}"
                                           target="_blank">
                                            {!! App\Icon::find("external-link-square")->wrap() !!}
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection


