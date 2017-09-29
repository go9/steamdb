@extends('main')

@section('title', ' | Edit Bundle')

@section('content')


    @section('actionbar-contents')
        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#bundle-modal">
            Add to Purchases
        </button>
        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#add-game-modal">
            Add a game
        </button>
        <button class="dropdown-item" type="button" data-toggle="modal" data-target="#bulk-import-modal">
            Bulk Importer
        </button>

        <button class="dropdown-item" href="/bundles/{!! $bundle->id !!}" >
            View Bundle
        </button>

        <script>$("#action-bar").show()</script>
    @endsection



    <div class="row">
        {{-- Print the games --}}
        @foreach($bundle->tiers() as $i => $tier)
            @if(sizeof($tier) > 0)
                <div style="width:100%;">
                    <h5 class="card-title">
                        Tier {{ $i }}
                    </h5>
                </div>
                @foreach($tier as $game)
                    <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3" style="padding:0px;">
                        <div class="card">
                            <img class="card-img"
                                 style="width:100%;height:auto;"
                                 src="{!! $game->headerImage()->url !!}"
                                 alt="Card image cap"
                            >
                        </div>
                        <div class="card-img-overlay">
                            <form method="POST" action="{{route("bundle.change_tier")}}">
                                {!! csrf_field() !!}
                                <input type="hidden" name="bundle_game_id" value="{!! $game->pivot->id !!}">

                                <a href="/games/{{$game->id}}"
                                   class="card-title"
                                   style="background-color:rgba(1,1,1,.6);color:white;font-size:1.4em;padding:5px;">
                                    {!! $game->name !!}
                                </a>

                                <br>

                                <span style="background-color:rgba(1,1,1,.6);color:white;font-size:1.4em;padding:5px;">
                                    <button name="new_tier" value="{{$i - 1}}" class="btn-empty">
                                        {!! App\Icon::find("arrow-up")->wrap(["color" => "white"]) !!}
                                    </button>

                                    <button name="new_tier" value="{{$i + 1}}" class="btn-empty">
                                        {!! App\Icon::find("arrow-down")->wrap(["color" => "white"]) !!}
                                    </button>

                                    <button name="new_tier" value="0" class="btn-empty">
                                        {!! App\Icon::find("trash")->wrap() !!}
                                    </button>
                                </span>
                                <p class="card-text"></p>
                            </form>
                        </div>
                    </div>
                @endforeach
            @endif
        @endforeach
    </div>
    </div>

    <!-- Bulk Import Modal -->
    @include("games.bulk_import")

    <!-- Add game to bundle Modal -->
    @include("games.game_import")


    <script>
        function processImport(result) {
            linkGamesToBundle(result);
        }

        function linkGamesToBundle(gameids, tier = 1) {
            $.ajax({
                url: '/bundles/link_game_bundle',
                type: 'POST',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'game_ids': gameids,
                    'bundle_id': {!! $bundle->id !!},
                    'tier': tier
                },
                dataType: 'json',
                success: function (json, response, three) {
                    location.reload();
                },
                error: function (request, error) {
                    console.log(request);
                }
            });
        }
    </script>

    <!-- Add game to Purchase Modal -->
    <div class="modal fade" id="bundle-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add To Purchase</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form class="form" id="submit-bundle-form" method="POST"
                      action="{{ action('PurchaseController@addBundleToPurchase') }}">
                    {{ csrf_field() }}
                    <input type="hidden" name="user_id" value="{!! Auth::user()->id !!}">
                    <input type="hidden" name="bundle_id" value="{!! $bundle->id !!}">
                    <input type="hidden" name="store_id" value="{!! $bundle->store_id !!}">
                    <input type="hidden" name="name" value="{!! $bundle->name !!}">
                    <div class="modal-body">
                        <div class="form-group row">
                            <label for="example-search-input" class="col-2 col-form-label">Purchase Date*</label>
                            <div class="col-10">
                                <input class="form-control" type="date" id="purchase-date" name="date_purchased">
                            </div>
                        </div>
                        <div class="form-group row">

                            <label for="example-email-input" class="col-2 col-form-label">Tier Purchased</label>
                            <div class="col-10">
                                <select id="purchase-tier" name="tier_purchased" class="form-control">
                                    @for($i = 1; $i <= count($bundle->tiers()); $i++)
                                        <option value="{!! $i !!}">Tier {!! $i !!}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-email-input" class="col-2 col-form-label">Price*</label>
                            <div class="col-10">
                                <input class="form-control" type="number" id="purchase-price" name="price_paid">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="example-text-input" class="col-2 col-form-label">Notes</label>
                            <div class="col-10">
                            <textarea
                                    class="form-control"
                                    id="purchase-notes"
                                    name="notes"
                                    value="">
                            </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection


