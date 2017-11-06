@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Drivers List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Drivers</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">

        <!-- Notification -->
        @include('layouts.default.notification')
        <!-- Notification -->

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Driver list</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                        <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                            <i class="fa fa-wrench"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-user">
                            <li><a href="#">Config option 1</a>
                            </li>
                            <li><a href="#">Config option 2</a>
                            </li>
                        </ul>
                        <a class="close-link">
                            <i class="fa fa-times"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <div class="col-md-8">
                            @if(Auth::user()->can('create-driver'))
                                <a href="{{ URL::to('driver/create') }}" class="btn btn-primary ">Create a new driver</a>
                            @endif
                        </div>
                        <div class="col-md-4" align="right">
                            <form  class="form-horizontal" role="form" method="GET" action="{{ url('driver') }}">
                            <div class="input-group"><input type="text" name="searchValue" placeholder="Search Driver" class="form-control"> <span class="input-group-btn"> <button type="submit" class="btn btn-primary"> Search
                                        </button> </span></div></form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Created At</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($drivers as $driver)
                                <tr class="gradeX">
                                    <td>{{$driver->email}}</td>
                                    <td>{{$driver->firstname}}</td>
                                    <td>{{$driver->lastname}}</td>
                                    <td>{{$driver->created_at}}</td>
                                    @if(Auth::user()->can('driver-list'))
                                        <td class="text-right">
                                            @if(Auth::user()->can('send-driver-individual-email'))
                                                <a href="{{ URL::to('send_driver_email') }}/{{$driver->driver_id}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are You Sure You Want Send Activate Email?')">Send Email</a>
                                            @endif
                                            @if(Auth::user()->can('view-driver'))
                                                <a href="{{ URL::to('driver/view') }}/{{$driver->driver_id}}" role="button" class="btn-white btn btn-xs">View</a>
                                            @endif
                                            @if(Auth::user()->can('edit-driver'))
                                                <a href="{{ URL::to('driver/edit') }}/{{$driver->driver_id}}" role="button" class="btn-white btn btn-xs">Edit</a>
                                            @endif
                                            @if(Auth::user()->can('delete-driver'))
                                                <a href="{{ URL::to('driver/delete') }}/{{$driver->id}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are you sure you want delete this user?')">Delete</a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Created At</th>
                                <th class="text-right">Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div align="right">@include('pagination.default', ['paginator' => $drivers])</div>
                    </div>
                     <form  class="form-horizontal" role="form" method="POST" action="{{ url('send_email_to_drivers') }}">
                    @if(Auth::user()->can('send-email-to-all-driver'))
                            <span class="input-group-btn" align="center"> <button type="submit" class="btn btn-primary">Send Email To All Driver</button></span>
                    @endif
                    </form
                </div>
            </div>
        </div>
    </div>
</div>
@endsection