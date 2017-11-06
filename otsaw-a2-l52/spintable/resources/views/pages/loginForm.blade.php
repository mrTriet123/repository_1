@extends('login')

@section('content')

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        @include('layouts.login.header')

        <h3>Welcome to FivMoon</h3>

        <form class="m-t" role="form" action="index.html">
            <div class="form-group">
                <input type="email" class="form-control" placeholder="Username" required="">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" required="">
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Login</button>

            <a href="{{ URL::to('forget-password') }}"><small>Forgot password?</small></a>
            <p class="text-muted text-center"><small>Do not have an account?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="{{ URL::to('register') }}">Create an account</a>
        </form>

        @include('layouts.login.footer')
    </div>
</div>

@endsection