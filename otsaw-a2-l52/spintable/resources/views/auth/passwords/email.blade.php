
@extends('login')

<!-- Main Content -->
@section('content')
<div class="passwordBox animated fadeInDown">
    <div class="row">
        <div class="col-md-12">
                <div class="ibox-content">

                    <h2 class="font-bold">Forgot password</h2>

                    <p>
                        Enter your email address and your password will be reset and emailed to you.
                    </p>

                    <div class="row">

                        <div class="col-lg-12">
                            <form class="m-t" role="form" method="POST" action="{{ url('/password/email') }}">
                               {!! csrf_field() !!}
                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <input type="email" class="form-control" name="email" placeholder="Email address" required="" value="{{ old('email') }}">
                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('email') }}</strong>
                                            </span>
                                        @endif
                                </div>

                                <button type="submit" class="btn btn-primary block full-width m-b"><i class="fa fa-btn fa-envelope"></i> Send Password Reset Link</button>
                           
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
    <hr/>
        <div class="row">
            <div class="col-md-6">
                Copyright OTSAW Digital Inc
            </div>
            <div class="col-md-6 text-right">
               <small>© 2015-2016</small>
            </div>
        </div>
</div>
@endsection


