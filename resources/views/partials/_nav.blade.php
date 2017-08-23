<nav class="navbar sticky-top navbar-toggleable-md navbar-light bg-faded" style="background-color:rgba(200,211,213)" ;>

    <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse"
            data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
            aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <a class="navbar-brand" href="/">
        <img class='img-responsive' style='height:60px;filter: invert(1);' src="../images/logo.png"/>
    </a>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class='nav-item'>
                <a class="nav-link" id='nav-item-library' href="/library">
                    <strong>Library</strong>
                </a>
            </li>

            <li class='nav-item'>
                <a class="nav-link" id='nav-item-inventory' href="/inventory">
                    <strong>Inventory</strong>
                </a>
            </li>

            <li class='nav-item'>
                <a class="nav-link" id='nav-item-purchases' href="/purchases">
                    <strong>Purchases</strong>
                </a>
            </li>

            <li class='nav-item'>
                <a class="nav-link" id='nav-item-bundles' href="/bundles">
                    <strong>Bundles</strong>
                </a>
            </li>
        </ul>
        <ul class="navbar-nav">

            <li class="nav-item" >
            @if(Auth::check())
                <!-- User is logged in -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="user-menu"
                            data-toggle="dropdown"
                            aria-haspopup="true" aria-expanded="false">
                        {{Auth::user()->name}}
                    </button>
                    <div class="dropdown-menu" aria-labelledby="user-menu">
                        <a class="dropdown-item" href="/settings">Settings</a>
                        <form method="post" action="{!!route("logout") !!}">
                            {{ csrf_field() }}
                            <button class="dropdown-item">Logout</button>
                        </form>
                    </div>
                </div>

            @else
                <!-- User is not logged in -->
                <a class='btn btn-primary' href="{{route('login')}}" style="margin:3px;">
                    Login
                </a>
            @endif
            </li>
        </ul>
    </div>
</nav>

<form action="/games" method="GET">
    <div id="menu-searchbar" class="col-sm-12 col-md-8 col-lg-6 input-group" style="float:right;margin-top:5px;margin-bottom:5px;">
        <input class="form-control" type="text" name="keywords" placeholder="Search">
        <button class="btn btn-outline-success" type="submit">Search</button>
    </div>
</form>

<div style="width:100%;height:1px;clear:both;margin-bottom:15px;"></div>

@if(strpos(url()->current(), "library") !== false)
    <script> $("#nav-item-library").addClass("active"); </script>
@elseif(strpos(url()->current(), "inventory") !== false)
    <script> $("#nav-item-inventory").addClass("active"); </script>
@elseif(strpos(url()->current(), "purchases") !== false)
    <script> $("#nav-item-purchases").addClass("active"); </script>
@elseif(strpos(url()->current(), "bundles") !== false)
    <script> $("#nav-item-bundles").addClass("active"); </script>
@endif


