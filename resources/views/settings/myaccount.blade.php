@extends('settings.index')

@section('settings-content')
    <div class="card" style="padding:15px;">
        <h4 class="card-title">My Account</h4>
        <p class="card-text">
        <table class="table">
            <tr>
                <td>Name</td>
                <td>{{Auth::user()->name}}</td>
            </tr>
            <tr>
                <td>Email</td>
                <td>{{Auth::user()->email}}</td>
            </tr>
        </table>
        </p>
    </div>
@endsection
