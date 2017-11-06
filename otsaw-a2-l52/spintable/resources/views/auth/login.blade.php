@extends('login')

@section('content')

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        @include('layouts.login.header')

        <h3>Welcome to QUABII</h3>

        <form class="m-t" role="form" method="POST" action="{{ url('/login') }}">
            {!! csrf_field() !!}

            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input type="email" class="form-control" name="email" placeholder="Email" required="" value="{{ old('email') }}">
                     @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <input type="password" class="form-control" placeholder="Password" name="password" required="">
                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                     @endif
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember"> Remember Me
                    </label>
                </div>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            <a href="{{ url('/password/reset') }}"><small>Forgot password?</small></a>
            {{--<p class="text-muted text-center"><small>Do not have an account?</small></p>--}}
            {{--<a class="btn btn-sm btn-white btn-block" href="{{ URL::to('driver/register') }}">Register Driver</a>--}}
        </form>

        @include('layouts.login.footer')
    </div>
</div>

@endsection
