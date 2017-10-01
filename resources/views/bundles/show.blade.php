@extends('main')
@section('title', ' | ' . $bundle->name)
@section('content')

    @if(Auth::check())
        @section('actionbar-contents')
            <button class="dropdown-item" type="button" data-toggle="modal" data-target="#bundle-modal">
                Add to Purchases
            </button>

             @if(Auth::user()->checkRole("admin"))
                <a class="dropdown-item" href='/bundles/{!! $bundle->id !!}/edit'>Edit</a>
            @endif

            <script>$("#action-bar").show()</script>
        @endsection
    @endif

    <!-- bundle content -->
    <div class="row">
        <div id="bundle-content" class="col-12">
            <!-- Bundle Contents-->
            <!-- Bundle title -->
            <span class="title">{!! $bundle->name !!}</span>

            <div id="bundle-info">
                    <table class="table table-bordered table-sm">
                        <tbody>
                        <tr>
                            <td>
                                <strong>Store</strong>
                            </td>
                            <td>
                                {!! $bundle->stores->name !!}
                            </td>
                        </tr>
                        @if($bundle->date_started)
                            <tr>
                                <td><strong>Started</strong></td>
                                <td>
                                    {!! \Carbon\Carbon::parse($bundle->date_started)->diffForHumans()!!}
                                </td>
                            </tr>
                        @endif
                        @if($bundle->date_ended)
                            <tr>
                                <td><strong>Ended</strong></td>
                                <td>
                                    {!! \Carbon\Carbon::parse($bundle->date_ended)->diffForHumans()!!}
                                </td>
                            </tr>
                        @endif

                        </tbody>
                    </table>
                </div>

            @foreach($bundle->tiers() as $i => $tier)
                <div class="col-12" style="background:rgba(1,1,1,.05);margin-bottom:15px;">
                    <div style="width:60px;padding:7px;">
                        <span style="text-transform: uppercase;font-weight: bold;font-size:.8em;">
                            Tier {{ $i }}
                        </span>
                    </div>
                    <div style="padding:7px;">
                    @include("games.games_printer", ["games" => $tier, "settings" => ["print_advanced" => false, "display_type" => "grid"]])
                    </div>
                </div>
            @endforeach
        </div>
    </div>

<!-- Add Bundle to Purchase Modal -->
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
                        <label for="example-search-input" class="col-4 col-form-label">Purchase Date*</label>
                        <div class="col-8">
                            <input class="form-control" type="date" id="purchase-date" name="date_purchased" value="{!! \Carbon\Carbon::today()->toDateString() !!}">
                        </div>
                    </div>
                    <div class="form-group row">

                        <label for="example-email-input" class="col-4 col-form-label">Tier Purchased</label>
                        <div class="col-8">
                            <select id="purchase-tier" name="tier_purchased" class="form-control">
                                @for($i = 1; $i <= count($bundle->tiers()); $i++)
                                    <option value="{!! $i !!}">Tier {!! $i !!}</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-email-input" class="col-4 col-form-label">Price paid</label>
                        <div class="col-8">
                            <input class="form-control" type="text" id="purchase-price" name="price_paid">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="example-text-input" class="col-4 col-form-label">Notes</label>
                        <div class="col-8">
                                    <textarea class="form-control" id="purchase-notes" name="notes"></textarea>
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



