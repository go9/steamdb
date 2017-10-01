@extends('main')

@section('title', ' | Bundles')

@section('content')

    <script> $("#nav-item-bundles").addClass("active");</script>

    @if(Auth::check() && Auth::user()->checkRole("admin"))
        @section('actionbar-contents')
            <button class="dropdown-item"
                    type="button"
                    data-toggle="modal"
                    data-target="#bundle-modal">Add new bundle
            </button>

            <script>$("#action-bar").show()</script>
        @endsection
    @endif


    {{-- Print the bundles --}}
    @foreach($bundles as $bundle)
        <div class="row" style="padding:5px;">
            {{-- Print the bundle title --}}
            <div class="card" style="background-color:transparent;width:100%;">
                <div class="card-block">
                    <span class="card-title">
                        <a class="title" href="/bundles/{{$bundle->id}}">{{$bundle->name}}</a>
                        {!! $bundle->stores->name !!}
                    </span>

                    <p class="card-text">
                        @include("games.games_printer", ["games" => $bundle->games, "settings" => ["print_advanced" => false, "display_type" => "grid"]])
                    </p>
                </div>
            </div>
        </div>
    @endforeach

    <div class="pagination pagination-sm justify-content-center"
         style="margin-top:10px;width:100%;overflow:hidden;">
        {{$bundles->links("vendor.pagination.bootstrap-4")}}
    </div>

@endsection

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
                <h5 class="modal-title" id="bundle-modal">Add New Bundle</h5>
            </div>
            <div class="modal-body container">

                <form class="form" id="submit-bundle-form" method="POST" action="{{route('bundles.store')}}">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label for="submit-bundle-name" class="col-4 col-form-label">Name</label>
                        <div class="col-8">
                            <input type="text" class="form-control" name="name" id="submit-bundle-name">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="submit-bundle-store" class="col-4 col-form-label">Store</label>
                        <div class="col-8">
                            <select class="form-control" name='store_id' data-live-search="true">
                                @foreach(App\Store::all() as $store)
                                    <option value="{{$store->id}}">{{$store->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="submit-bundle-start-date" class="col-4 col-form-label">Date Started</label>
                        <div class="col-8">
                            <input type="date" class="form-control" name="date_started" id="submit-bundle-start-date">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="submit-bundle-end-date" class="col-4 col-form-label">Date Ended</label>
                        <div class="col-8">
                            <input type="date" class="form-control" name="date_ended" id="submit-bundle-end-date">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="submit-bundle-notes" class="col-4 col-form-label">Notes</label>
                        <div class="col-8">
                            <textarea type="text" class="form-control" name="notes" id="submit-bundle-notes"></textarea>
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