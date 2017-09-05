@extends('main')
@section('title', ' | Login')
@section('content')

    <div class="row">
        <div class="col-sm-12 col-md-6 col-lg-3" style="margin:0 auto;">
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
                            Remember Me
                        </label>
                    </div>

                </div>

                <button type="submit" class="btn btn-primary">
                    Login
                </button>
            </form>

            <hr>

            <div style="width:100%;">
                <a href="/auth/steam/handle">
                    <img style="width:auto;margin:0 auto;" src="https://steamcommunity-a.akamaihd.net/public/images/signinthroughsteam/sits_01.png">
                </a>
            </div>

        </div>
    </div>



@endsection
