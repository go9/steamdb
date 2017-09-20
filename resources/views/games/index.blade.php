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

    <!-- Advanced Search -->
    <div class="row" style="padding:15px;">
        {{-- Since we have this advanced search, we don't need the navbar search anymnore --}}
        <script>
            $("#menu-searchbar").hide();
        </script>

        {{-- Advanced Search --}}
        <form class="form" id="advanced-search" style="width:100%;padding:10px;padding-top:30px;">
            <div class="form-group row">
                <div class="col-12 col-sm-6 col-md-8 col-xl-8">
                    <input type="text"
                           class="form-control form-control-sm"
                           id="form-keyword"
                           name="keywords"
                           placeholder="Search Term: Ex. 'Grand Theft Auto V'"
                           value="{{
                                isset($_GET['keywords']) ? $_GET['keywords'] : null
                           }}">

                </div>

                <div class="col-12 col-sm-3 col-md-2 col-xl-2">
                    <button class="btn btn-primary btn-sm" style="width:100%;" type="submit">Search</button>
                </div>

                <div class="col-12 pt col-sm-3 col-md-2">
                    <a class="btn btn-secondary btn-sm" style="width:100%;" data-toggle="collapse" href="#form-advanced">Advanced</a>
                </div>

            </div>

            <div class="collapse" id="form-advanced">
                <div class="form-group row">
                    <div class="col">
                        <label for="form-type">Type</label>
                        <div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    @php($type = isset($_GET['types']) ? $_GET['types'] : null)
                                    <input
                                            {!! isset($_GET['types']) && in_array("game",$_GET['types']) ? "checked" : "" !!}
                                            class="form-check-input"
                                            type="checkbox"
                                            id="form-type"
                                            name="types[]"
                                            value="game"> Game
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input
                                            {!! isset($_GET['types']) && in_array("dlc",$_GET['types']) ? "checked" : "" !!}
                                            class="form-check-input"
                                            type="checkbox"
                                            id="form-type"
                                            name="types[]"
                                            value="dlc"> DLC
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <label class="form-check-label">
                                    <input
                                            {!! isset($_GET['types']) && in_array("package",$_GET['types']) ? "checked" : "" !!}
                                            class="form-check-input"
                                            type="checkbox"
                                            id="form-type"
                                            name="types[]"
                                            value="package">Package
                                </label>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="form-group row">
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label for="filter-genre">Genre</label>
                        <select multiple class="form-control" id="filter-genre" name="genres[]">
                            @foreach(App\Genre::all() as $genre)
                                <option value="{{$genre->id}}"
                                        {{isset($_GET['genres']) && in_array($genre->id, $_GET['genres'])
                                        ? "selected"
                                        : ""
                                        }}
                                >{{$genre->name}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-3">
                        <label for="filter-category">Category</label>
                        <select multiple class="form-control" id="filter-category" name="categories[]">
                            @foreach(App\Category::all() as $category)
                                <option value="{{$category->id}}"
                                        {{isset($_GET['categories']) && in_array($category->id, $_GET['categories'])
                                        ? "selected"
                                        : ""
                                        }}
                                >{{$category->name}}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="ccol-xs-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="filter-language">Language</label>
                        <select multiple class="form-control" id="filter-language" name="languages[]">
                            @foreach(App\Language::all() as $language)
                                <option value="{{$language->id}}"
                                        {{isset($_GET['languages']) && in_array($language->id, $_GET['languages'])
                                        ? "selected"
                                        : ""
                                        }}
                                >{{$language->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="filter-developer">Developer</label>
                        <select multiple class="form-control" id="filter-developer" name="developers[]">
                            @foreach(App\Developer::all() as $developer)
                                <option value="{{$developer->id}}"
                                        {{isset($_GET['developers']) && in_array($developer->id, $_GET['developers'])
                                        ? "selected"
                                        : ""
                                        }}

                                >{{$developer->name}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2">
                        <label for="filter-publisher">Publisher</label>
                        <select multiple class="form-control" id="filter-publisher" name="publishers[]">
                            @foreach(App\Publisher::all() as $publisher)
                                <option value="{{$publisher->id}}"
                                        {{isset($_GET['publishers']) && in_array($publisher->id, $_GET['publishers'])
                                        ? "selected"
                                        : ""
                                        }}
                                >{{$publisher->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-2" style="padding-bottom:10px;">
                    <button class="btn" style="width:100%;" type="reset">Reset</button>
                </div>
            </div>

            <div class="form-inline" style="float:right;">

                <div class="form-group">
                    <label class="mr-sm-2" for="sorting">Sort By</label>
                    <select
                            form="advanced-search"
                            class="form-control form-control-sm"
                            id="sorting"
                            name="sorting"
                            onchange="this.form.submit()">
                        <option value="name-asc" {{isset($_GET['sorting']) && $_GET['sorting'] == "name-asc" ? "selected" : ""}}>
                            A-Z
                        </option>
                        <option value="name-desc" {{isset($_GET['sorting']) && $_GET['sorting'] == "name-desc" ? "selected" : ""}}>
                            Z-A
                        </option>
                    </select>
                </div>

                <div class="form-group  mx-sm-3">
                    <label class="mr-sm-2" for="sorting">Results</label>
                    <select
                            form="advanced-search"
                            class="form-control form-control-sm"
                            id="sorting"
                            name="display"
                            onchange="this.form.submit()">
                        <option value="12" {{isset($_GET['display']) && $_GET['display'] == "12" ? "selected" : ""}}>
                            12
                        </option>
                        <option value="24" {{isset($_GET['display']) && $_GET['display'] == "24" ? "selected" : ""}}>
                            24
                        </option>
                        <option value="36" {{isset($_GET['display']) && $_GET['display'] == "36" ? "selected" : ""}}>
                            36
                        </option>
                    </select>
                </div>
            </div>
        </form>





        <div style="width:100%;clear:both;"></div>

        <script>
            var input = {!! json_encode($_GET) !!};
            for(var attribute in input){
                //console.log(input[attribute]);
            }

        </script>

    </div>

    <div class="row">
        {{-- Check if games there are any games to show. --}}
        @if(sizeof($games) == 0)
            <div class="alert alert-danger" role="alert">
                <strong>Whoops!</strong> Nothing to see here.
            </div>
        @else

            {{-- Print the games --}}
            @foreach($games as $key => $game)
                <div class="game card col-xs-12 col-sm-6 col-md-4 col-lg-3" style="padding:0px;">
                    <img class="card-img"
                         style="width:100%;height:auto;"
                         src="{!! $game->headerImage()->url !!}"
                         alt="Card image cap">
                    <div class="card-img-overlay">
                        <a href="/games/{{$game->id}}"
                           class="card-title game-title" style="font-size:1.3em;">
                            {!! $game->name !!}
                        </a>

                        @if(Auth::user())
                            @if(Auth::user()->isIn($game->id, "inventory"))
                                @php($icon = App\Icon::find("archive"))
                                <a class="card-title"
                                   style="
                                           background-color:rgba(1,1,1,.6);
                                           font-size:1.4em;
                                           padding:5px;">
                                    {!! $icon->wrap() !!}
                                </a>
                            @endif

                            @if(Auth::user()->isIn($game->id, "library"))
                                @php($icon = App\Icon::find("book"))
                                <a class="card-title"
                                   style="background-color:rgba(1,1,1,.6);
                                       font-size:1.4em;
                                       padding:5px;">
                                    {!! $icon->wrap() !!}
                                </a>
                            @endif
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>


    @if($games instanceof \Illuminate\Pagination\LengthAwarePaginator )
        <div class="pagination pagination-sm justify-content-center" style="margin-top:10px;width:100%;overflow:hidden;">
            {{$games->appends(Request::except("page"))->links("vendor.pagination.bootstrap-4")}}
        </div>

    @endif

@endsection
