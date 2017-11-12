@extends('main')

@section('title')

@section('content')

    @if(Auth::check())
@section('actionbar-contents')

        <button class="dropdown-item">
            Hello!
        </button>
        <button class="dropdown-item" onclick="startConversation()">
            Start Conversation
        </button>

    <script>
        function startConversation(){
            $.ajax({
                url: "/conversation/create",
                type: "POST",
               data: {
                    _token: "{{csrf_token()}}",
                   user_ids : [
                       {{ $user->id }},
                       {{ Auth::user()->id }}
                   ]
               },
                success: function(json){
                    console.log(json);
                }
            });
        }

    </script>

    @if(Auth::user()->checkRole("admin"))
    @endif

    <script>$("#action-bar").show()</script>
@endsection


@endif

    <div>
        {{$user->name}}
        @if($user->checkSteam())
            {!! App\Icon::find("check")->wrap(["color" => "#90C2E7", "title" => "Verified with Steam"]) !!}
        @endif
        <img src="{!! $user->avatar !!}">
    </div>


@endsection
