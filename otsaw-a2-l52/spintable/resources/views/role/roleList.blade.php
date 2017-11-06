@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Role List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Roles</strong>
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
                    <h5>Role List</h5>
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
                        @if(Auth::user()->can('create-role'))
                            <a href="{{ URL::to('role/create') }}" class="btn btn-primary ">Create a new role</a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                @if(Auth::user()->can('view-role'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $role)
                                <tr class="gradeX">
                                    <td>{{$role['name']}}</td>
                                    <td>{{$role['display_name']}}</td>
                                    <td>{{$role['description']}}</td>
                                    @if(Auth::user()->can('view-role'))
                                        <td class="text-right">
                                            <a href="{{ URL::to('role/view') }}/{{$role['id']}}" role="button" class="btn-white btn btn-xs">View</a>
                                            @if(Auth::user()->can('edit-role'))
                                                <a href="{{ URL::to('role/edit') }}/{{$role['id']}}" role="button" class="btn-white btn btn-xs">Edit</a>
                                            @endif
                                            @if(Auth::user()->can('delete-role'))
                                                <a href="{{ URL::to('role/delete') }}/{{$role['id']}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are you sure you want delete this role?')">Delete</a>
                                            @endif
                                        </td>
                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                @if(Auth::user()->can('view-role'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection