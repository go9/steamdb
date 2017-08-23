@extends('main')

@section('title', " | Add Game")

@section('content')
    <form class="form" method="post" action="/games">
        {!! csrf_field() !!}
        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control" type="text" name="name" id="name" value="{!! old("name") !!}">
        </div>

        <button class="btn btn-primary" type="submit">Submit</button>
    </form>
@endsection