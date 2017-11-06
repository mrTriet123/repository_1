@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Users list</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Users</strong>
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
                    <h5>User list with different roles</h5>
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
                    <div class="">
                        @if(Auth::user()->can('create-user'))
                            <a href="{{ URL::to('user/create') }}" class="btn btn-primary ">Create a new user</a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Role</th>
                                @if(Auth::user()->can('view-user'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($users as $user)
                                <tr class="gradeX">
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->firstname}}</td>
                                    <td>{{$user->lastname}}</td>
                                    <td>{{$user->role}}</td>
                                    @if(Auth::user()->can('view-user'))
                                        <td class="text-right">
                                            <a href="{{ URL::to('user/view') }}/{{$user->id}}" role="button" class="btn-white btn btn-xs">View</a>
                                            @if(Auth::user()->can('edit-user'))
                                                <a href="{{ URL::to('user/edit') }}/{{$user->id}}" role="button" class="btn-white btn btn-xs">Edit</a>
                                            @endif
                                            @if(Auth::user()->can('delete-user'))
                                                <a href="{{ URL::to('user/delete') }}/{{$user->id}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are you sure you want delete this User?')">Delete</a>
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
                                <th>Role</th>
                                @if(Auth::user()->can('view-user'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                        </table>
                        <div align="right">@include('pagination.default', ['paginator' => $users])</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection