@extends('layouts.email.master')

@section('content')
<table  cellpadding="0" cellspacing="0">
    @include('layouts.email.logo')
    <tr>
        <td class="content-block">
            <h3>Hello, {Username},</h3>
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Thank you for registering with FIV.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            FIV is a chauffeur-esque service app that matches drivers to car owners who wish to be transported to and from destinations in their own vehicle.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Your application is being reviewed by our HR team and we will be contacting you shortly.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            In the meantime, do visit our website at <a href="http://www.fiv.sg" target="_blank">www.fiv.sg</a> to understand more about our services.
        </td>
    </tr>
    <tr>
        <td class="content-block">
            Much Love,<br/>
            The FIV Team
        </td>
    </tr>
</table>
@endsection