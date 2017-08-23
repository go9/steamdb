@extends('main')

@section('title', ' | New Post')

@section('content')

    <script> $("#nav-item-bundles").addClass("active");</script>

    @if(Auth::check())
        <!-- Add New -->
        <div class="row" style="width:100%;padding:15px;">
            <button class='btn btn-primary'
                    type="button"
                    data-toggle="modal"
                    data-target="#bundle-modal">Add new bundle
            </button>
        </div>

        <!-- Add to bundle Modal-->
        <div class="modal fade"
             id="bundle-modal"
             tabindex="-1"
             role="dialog"
             aria-labelledby="exampleModalLabel"
             aria-hidden="true">

            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bundle-modal">Bundle Editor</h5>
                    </div>
                    <div class="modal-body container">

                        <form class="form" id="submit-bundle-form" method="POST" action="{{route('bundles.store')}}">
                            {{ csrf_field() }}
                            <div class="form-group row">
                                <label for="submit-bundle-name" class="col-2 col-form-label">Name</label>
                                <div class="col-10">
                                    <input type="text" class="form-control" name="name" id="submit-bundle-name">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="submit-bundle-store" class="col-2 col-form-label">Store</label>
                                <div class="col-10">
                                    <select class="form-control" name='store_id' data-live-search="true">
                                        @foreach(App\Store::all() as $store)
                                            <option value="{{$store->id}}">{{$store->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button for="submit-bundle-form" type="submit" class="btn btn-primary">Submit</button>
                    </div>

                    </form>
                </div>
            </div>
        </div>
    @endif


    {{-- Print the bundles --}}
    <style>
        .bundle {
            margin: 10px;
            padding: 15px;
        }
    </style>

    @foreach($bundles as $bundle)
        <div class="row bundle">
            {{-- Print the bundle title --}}
            <div>
                <a
                        style="text-decoration: none;color:black;"
                        href="/bundles/{{$bundle->id}}"><h4>{{$bundle->name}}</h4> </a>
            </div>

            {{-- New line--}} <div style="width:100%;"></div>

            {{-- Print the games --}}
            @php
                $tierMax = 1;
                $gamesByTier = [];
                foreach($bundle->games as $bundleItem){
                    // Look for the max tier
                    if($bundleItem->pivot->tier > $tierMax){
                      $tierMax = $bundleItem->pivot->tier;
                    }

                    // Add to games
                    $gamesByTier[$bundleItem->pivot->tier][] = $bundleItem;
                }
            @endphp

            @if(count($bundle->games) == 0)
                <div class="alert alert-info">
                    There are no games in this bundle.
                </div>
            @else
                @foreach($gamesByTier as $i => $tier)
                    @if(sizeof($tier) > 0)
                        @foreach($tier as $game)
                            @php
                                // Get banner url
                                $image = $game->images->where("type","header_image")->first();
                                $image = $image == null
                                    ? "http://cdn.akamai.steamstatic.com/steam/apps/10/header.jpg?t=1447887426"
                                    : $image->url;
                            @endphp
                            <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="padding:0px;">
                                <div class="card">
                                    <img class="card-img"
                                         style="width:100%;height:auto;"
                                         src="{{$image}}"
                                         alt="Card image cap">
                                </div>
                                <div class="card-img-overlay">
                                    <a href="games/{{$game->id}}"
                                       class="card-title"
                                       style="background-color:rgba(1,1,1,.6);color:white;font-size:1.4em;padding:5px;">
                                        {!! $game->name !!}
                                    </a>
                                    <p class="card-text">
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endif
        </div>
        <hr>
    @endforeach

    <div class="pagination pagination-sm justify-content-center"
         style="margin-top:10px;width:100%;overflow:hidden;">
        {{$bundles->links("vendor.pagination.bootstrap-4")}}
    </div>

@endsection
