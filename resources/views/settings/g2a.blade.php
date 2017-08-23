@extends('settings.index')

@section('settings-content')
    @php($isEnabled = Auth::check() && Auth::user()->checkRole("g2a"))

    <div class="card" style="padding:15px;">
        <h4 class="card-title">
            G2a Settings
            {!! App\Icon::find("circle")->wrap(["color" => $isEnabled ? "green" : "red"]) !!}
        </h4>
        <form class="form" method="POST" action="{!! route("settings.toggle_g2a") !!}">
            {{ csrf_field() }}
            <input type="hidden" name="user_id" value="{!! Auth::user()->id !!}">
            {{-- Check if the user has access --}}
            @if($isEnabled)
                <button class="btn btn-danger col-xs-12 col-lg-3 col-xl-2">Disable G2A</button>

            @else
                <div class="alert alert-warning" style="width:100%;">
                    <strong>Caution!</strong>
                    G2a is a gray market website. You can read more about them here (coming soon).
                    Enabling this feature will allow you to track and view G2A values for your games.
                </div>
                <button class="btn btn-primary col-xs-12 col-lg-3 col-xl-2">Enable G2A</button>
            @endif
        </form>
    </div>
@endsection
