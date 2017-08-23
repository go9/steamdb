@if(Session::has('message-success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <strong> Success! </strong> {{ Session::get('message-success') }}
    </div>
@elseif(Session::has('message-info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('message-info') }}
    </div>
@elseif(Session::has('message-danger'))
    <div class="alert alert-danger" role="alert">
        <strong> Error! </strong> {{ Session::get('message-danger') }}
    </div>
@elseif(Session::has('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        {{ Session::get('message') }}
    </div>
@endif


@if(count($errors) > 0)
    <div class="alert alert-danger" role="alert">
        <strong> Error! </strong>
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif