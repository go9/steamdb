<div id="nav-container-top">
    <div class="container">
        <div class="row" style="padding-top:15px;">
            <!-- Display logo -->
            <div class="col-6">
                <a href="/">
                    <img id="nav-logo" class='img-responsive' src="../images/logo.png"/>
                </a>
            </div>

            <!-- Display user bar/login button -->
            <div class="col-6">
                @if(Auth::check())
                    <!-- User is logged in -->
                        <div id="user-bar">
                            <div id="user-bar-dropdown" class="dropdown">
                                <button id="user-menu" class="btn btn-empty dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{Auth::user()->name}}
                                    @if(Auth::user()->checkSteam())
                                        {!! App\Icon::find("check")->wrap(["color" => "#90C2E7", "title" => "Verified with Steam"]) !!}
                                    @endif
                                </button>

                                <div class="dropdown-menu" aria-labelledby="user-menu">
                                    <a class="dropdown-item" href="/settings">Settings</a>
                                    <form method="post" action="{!!route("logout") !!}">
                                        {{ csrf_field() }}
                                        <button class="dropdown-item">Logout</button>
                                    </form>
                                </div>
                            </div>
                            <img id="user-bar-avatar" src="{!! Auth::user()->avatar !!}">
                        </div>
                @else
                    <!-- User is not logged in -->
                        <div id="nav-login">
                            <a class='btn btn-empty' href="{{route('login')}}" style="color:white;">
                                {!! App\Icon::find("user")->wrap(["color" => "white"]) !!} login
                            </a>
                        </div>
                @endif
            </div>
        </div>
    </div>
</div>

<nav id="nav-container-bottom" class="sticky-top">
    <div class="container" style="display:block;white-space:nowrap;">
        <div class='menu-item'>
            <div style="height:5px;width:100%;background-color:{!! App\Icon::find("book")->default_color !!};"></div>
            <a class="nav-link" id='nav-item-library' href="/library">
                {!! App\Icon::find("book")->wrap() !!}
                <strong>Library</strong>
            </a>
        </div>

        <div class='menu-item' id="main-nav-inventory">
            <div style="height:5px;width:100%;background-color:{!! App\Icon::find("archive")->default_color !!};"></div>

            <a class="nav-link" id='nav-item-inventory' href="/inventory">
                {!! App\Icon::find("archive")->wrap() !!}
                <strong>Inventory</strong>
            </a>
        </div>

        <div class='menu-item'>
            <div style="height:5px;width:100%;background-color:transparent;"></div>
            <a class="nav-link" id='nav-item-purchases' href="/purchases">
                <strong>Purchases</strong>
            </a>
        </div>

        <div class='menu-item'>
            <div style="height:5px;width:100%;background-color:transparent;"></div>
            <a class="nav-link" id='nav-item-bundles' href="/bundles">
                <strong>Bundles</strong>
            </a>
        </div>
    </div>
</nav>


@if(strpos(url()->current(), "library") !== false)
    <script> $("#nav-item-library").addClass("active"); </script>
@elseif(strpos(url()->current(), "inventory") !== false)
    <script> $("#nav-item-inventory").addClass("active"); </script>
@elseif(strpos(url()->current(), "purchases") !== false)
    <script> $("#nav-item-purchases").addClass("active"); </script>
@elseif(strpos(url()->current(), "bundles") !== false)
    <script> $("#nav-item-bundles").addClass("active"); </script>
@endif


