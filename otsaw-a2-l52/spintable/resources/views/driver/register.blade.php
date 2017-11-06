@extends('login')

@section('content')

<div class="middle-box text-center loginscreen animated fadeInDown">
    <div>
        @include('layouts.login.header')

        <h3>Welcome to FIV</h3>

        <form class="m-t" role="form" method="POST" action="{{ url('driver/register') }}" enctype="multipart/form-data" file="true">
        {!! csrf_field() !!}
            <div class="form-group">
                {{-- <input type="text" class="form-control" placeholder="Name" required=""> --}}
                <input type="text" class="form-control" name="firstname" placeholder="FirstName" required="" value="{{ old('firstname') }}">
                @if ($errors->has('firstname'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="email" class="form-control" placeholder="Email" required=""> --}}
                <input type="text" class="form-control" name="lastname" placeholder="LastName" required="" value="{{ old('lastname') }}">
                @if ($errors->has('lastname'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('lastname') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ old('email') }}">
                @if ($errors->has('email'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('email') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="password" class="form-control" name="password" placeholder="Password" required="" value="{{ old('password') }}">
                @if ($errors->has('password'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('password') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="password" class="form-control" name="password_confirmation" required="" placeholder="Password Confirmation" value="{{ old('password_confirmation') }}">
                @if ($errors->has('password_confirmation'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('password_confirmation') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="text" class="form-control" name="gender" placeholder="Gender" required="" value="{{ old('gender') }}">
                @if ($errors->has('gender'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('gender') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="date" class="form-control" name="date_of_birthday" placeholder="Date of Birth" required="" value="{{ old('date_of_birthday') }}">
                @if ($errors->has('date_of_birthday'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('date_of_birthday') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <input type="text" class="form-control" name="contact_no" placeholder="Contact_no" required="" value="{{ old('contact_no') }}">
                @if ($errors->has('contact_no'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('contact_no') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                {{-- <input type="password" class="form-control" placeholder="Password" required=""> --}}
                <textarea class="form-control" rows="4" name="address" placeholder="address" required="">{{ old('address') }}</textarea>
                @if ($errors->has('address'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('address') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label style="float: left;">Profile Image</label>
               <input type="file" class="form-control" name="profile" placeholder="Profile Image" required="" value="{{ old('profile') }}">
                @if ($errors->has('profile'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('profile') }}</strong>
                    </span>
                @endif
            </div>
            <div class="form-group">
                <label style="float: left;">License</label>
               <input type="file" class="form-control" name="license" placeholder="License" required="" value="{{ old('license') }}">
                @if ($errors->has('license'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('license') }}</strong>
                    </span>
                @endif
            </div>
            {{-- <div class="form-group">
            <label style="float: left;">Video</label>
               <input type="file" class="form-control" name="video" placeholder="Video" required="" value="{{ old('video') }}">
                @if ($errors->has('video'))
                    <span class="help-block">
                    <strong style="color:red;">{{ $errors->first('video') }}</strong>
                    </span>
                @endif
            </div> --}}
            <div class="form-group">
                    <div class="checkbox i-checks"><label> <input type="checkbox" required=""><i></i> Agree the terms and policy </label></div>
            </div>
            <button type="submit" class="btn btn-primary block full-width m-b">Register</button>

            <p class="text-muted text-center"><small>Already have an account?</small></p>
            <a class="btn btn-sm btn-white btn-block" href="{{URL::to('/')}}">Login</a>
        </form>


        <!-- iCheck -->
        {{--<script>--}}
            {{--$(document).ready(function(){--}}
                {{--$('.i-checks').iCheck({--}}
                    {{--checkboxClass: 'icheckbox_square-green',--}}
                    {{--radioClass: 'iradio_square-green',--}}
                {{--});--}}
            {{--});--}}
        {{--</script>--}}

        @include('layouts.login.footer')
    </div>
</div>

@endsection