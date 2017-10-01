@php

    if(isset($_GET['display_type'])){
        session()->put('display_type', $_GET['display_type']);
        unset($_GET['display_type']);
    }
    $defaultSettings = [
        "display_type" => session()->has('display_type') ? session()->get('display_type') : "grid",
        "print_advanced" => false,
    ];

    $settings = isset($settings) ? array_merge ($defaultSettings, $settings) : $defaultSettings;
@endphp

<div class="row">
    {{-- Print advanced search --}}
    @if($settings["print_advanced"])
        <form class="form" id="advanced-search" style="width:100%;padding:10px;padding-top:30px;">
            <div class="form-group row">
                <div class="col-12 col-sm-6 col-md-8 col-xl-8">
                    <input type="text"
                           class="form-control"
                           id="form-keyword"
                           name="keywords"
                           placeholder="Search Term: Ex. 'Grand Theft Auto V'"
                           value="{{
                                isset($_GET['keywords']) ? $_GET['keywords'] : null
                           }}">

                </div>

                <div class="col-12 col-sm-3 col-md-2 col-xl-2">
                    <button class="btn btn-primary" style="width:100%;" type="submit">Search</button>
                </div>

                <div class="col-12 pt col-sm-3 col-md-2">
                    <a class="btn btn-secondary" style="width:100%;" data-toggle="collapse"
                       href="#form-advanced">Advanced</a>
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

            <div class="form-inline" style="float:left;">
                <div class="form-group" style="margin:5px;">
                    <label class="mr-sm-2" for="displayType"></label>
                    <div id='displayType' class="btn-group" data-toggle="buttons">
                        <label class="btn btn-secondary {!!$settings["display_type"] == "grid" ? " active" : "" !!}">
                            <input type="radio"
                                   form="advanced-search"
                                   name="display_type"
                                   value="grid"
                                   autocomplete="off"
                                   onchange="this.form.submit()">
                            <i class="fa fa-th-large" aria-hidden="true"></i>
                        </label>

                        <label class="btn btn-secondary {!!$settings["display_type"] == "list" ? " active" : "" !!}">
                            <input type="radio"
                                   form="advanced-search"
                                   name="display_type"
                                   value="list"
                                   autocomplete="off"
                                   onchange="this.form.submit()">
                            <i class="fa fa-th-list" aria-hidden="true"></i>
                        </label>
                    </div>
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
    @endif

    {{-- Print the games --}}

    @if($settings["display_type"] == "grid")
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
                @if(Auth::check() && Auth::user()->checkRole("g2a") && ($price = $game->prices->sortByDesc("created_at")->first()))
                    <div style="background-color:black;color:white;bottom:0;right:0;position: absolute;padding:5px;">
                        {!! money($game->newest_g2a_price) !!}
                    </div>
                @endif


            </div>
        @endforeach
    @elseif($settings["display_type"] == "list")
        <table class="table table-bordered table-sm">
            <tbody>
            @foreach($games as $key => $game)
                <tr>
                <!--
                    <td>
                        <img class="responsive" style="width:100%;" src="{!! $game->headerImage()->url !!}">
                    </td>
                    -->
                    <td style="padding:15px;">
                        @if(Auth::user() && Auth::user()->isIn($game->id, "inventory"))
                            <a>
                                {!! App\Icon::find("archive")->wrap() !!}
                            </a>
                        @endif

                        @if(Auth::user() && Auth::user()->isIn($game->id, "library"))
                            <a>
                                {!! App\Icon::find("book")->wrap() !!}
                            </a>
                        @endif

                        <a href="/games/{{$game->id}}">{!! $game->name !!}</a> <br>
                        {!! $game->type != "game" ? $game->type : ""!!}
                    </td>
                    <td style="padding:15px;">
                        @if(Auth::check() && Auth::user()->checkRole("g2a") && ($price = $game->prices->sortByDesc("created_at")->first()))

                            {!! money($price->pivot->price) !!}

                        @endif
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif


</div>


@if($games instanceof \Illuminate\Pagination\LengthAwarePaginator )
    <div class="pagination pagination-sm justify-content-center" style="margin-top:10px;width:100%;overflow:hidden;">
        {{$games->appends(Request::except("page"))->links("vendor.pagination.bootstrap-4")}}
    </div>

@endif