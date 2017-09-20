@extends('main')
@section('title', ' | Login')
@section('content')

    <div class="row" style="padding:15px;">

        <div class="col-sm-12 col-md-6" style="padding:20px;">


            <div style="padding:10px;">
                <h3>Sign in</h3>

                <form class="form" role="form" method="POST" action="{{ route('login') }}">
                    {{ csrf_field() }}
                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">

                        <div class="form-group">
                            <label for="email">Email address</label>
                            <input id="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   type="email" class="form-control"
                                   placeholder="Enter email"
                                   required
                                   autofocus>
                        </div>

                        <div class="form-group">
                            <label for="password">
                                Password
                                <a href="/password/reset">
                                    (Forgot)
                                </a>
                            </label>
                            <input id="password" name="password" type="password" class="form-control"
                                   id="exampleInputPassword1" placeholder="Password" required>
                        </div>

                        <div class="form-check">
                            <label class="form-check-label">
                                <input type="checkbox" class="form-check-input"
                                       name="remember" {{ old('remember') ? 'checked' : '' }}>
                                Remember me on this computer
                            </label>
                        </div>

                    </div>

                    <button type="submit" class="btn btn-secondary" style="width:100%;">
                        Login
                    </button>
                </form>
            </div>

            <hr>

            <div class="col-12">
                <div style="width:180px;margin:0 auto;">
                    <a href="/auth/steam/handle">
                        <img style="width:auto;margin:0 auto;"
                             src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                    </a>
                </div>
            </div>
        </div>

        <div class="col-sm-12 col-md-6" style="padding:20px;">
            <div style="padding:10px;">
                <h3>Register</h3>
                <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                    {{ csrf_field() }}

                    @if(isset($info))
                        {!! var_dump($info) !!}
                        <input type="hidden" name="steamid" value="{{ $info->steamID64 }}">
                        <input type="hidden" name="avatar" value="{{ $info->avatar }}">
                    @endif


                    <div class="form-group">
                        <label for="email">Email</label>
                        <input id="email" type="email" class="form-control" name="email"
                               value="{{ old('email') }}" required>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}"
                               required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password</label>
                        <input id="password" type="password" class="form-control" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="password-confirm">Confirm Password</label>
                        <input id="password-confirm" type="password" class="form-control"
                               name="password_confirmation" required>
                    </div>

                    <button type="submit" class="btn btn-secondary" style="width:100%;">
                        Register
                    </button>
                </form>
            </div>
        </div>
    </div>


@endsection
