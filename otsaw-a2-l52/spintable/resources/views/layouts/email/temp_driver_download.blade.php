@extends('layouts.email.driver_master')

@section('content')
<table  cellpadding="0" cellspacing="0">
    @include('layouts.email.logo')
    <tr>
        <td class="content-block">
            <h3>Hello {{$firstname}} {{$lastname}},</h3>
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Congratulations! And welcome on board to the FIV family.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Awesome, you’ve successfully registered a driver account. Please log in using this Username: <strong>{{$email}}</strong> and temporary password <strong>{{$password}}</strong> to activate your account. 
        </td>
    </tr>
    <tr>
        <td class="content-block" style="text-align:center;">
        <h3 style="margin-top: 10px;">Download the android app for FIV driver and receive your first customer request here</h3><br/>
            <a href="https://play.google.com/store/apps/details?id=com.otsaw.www.fiv&hl=en" target="_blank">
                <img src="https://s3-ap-southeast-1.amazonaws.com/otsaw/email/fiv-android-driver.png" style="width:80px;height:80px;"/>
            </a>
        </td>
    </tr>
    <tr>
        <td class="content-block">
            <br/>
            Much Love,<br/>
            The FIV Team
        </td>
    </tr>
</table>
@endsection