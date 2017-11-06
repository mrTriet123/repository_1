@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Permission List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Permissions</strong>
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
                    <h5>Permission List</h5>
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
                        @if(Auth::user()->can('create-permission'))
                            <a href="{{ URL::to('permissions/create') }}" class="btn btn-primary ">Create a new Permission</a>
                        @endif
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Name</th>
                                <th>Display Name</th>
                                <th>Description</th>
                                @if(Auth::user()->can('view-permission') || Auth::user()->can('edit-permission') || Auth::user()->can('delete-permission'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $permission)
                                <tr class="gradeX">
                                    <td>{{$permission['name']}}</td>
                                    <td>{{$permission['display_name']}}</td>
                                    <td>{{$permission['description']}}</td>
                                    @if(Auth::user()->can('view-permission') || Auth::user()->can('edit-permission') || Auth::user()->can('delete-permission'))
                                        <td class="text-right">
                                            @if(Auth::user()->can('view-permission'))
                                            <a href="{{ URL::to('permissions/view') }}/{{$permission['id']}}" role="button" class="btn-white btn btn-xs">View</a>
                                            @endif
                                            @if(Auth::user()->can('edit-permission'))
                                                <a href="{{ URL::to('permissions/edit') }}/{{$permission['id']}}" role="button" class="btn-white btn btn-xs">Edit</a>
                                            @endif
                                            @if(Auth::user()->can('delete-permission'))
                                                <a href="{{ URL::to('permissions/delete') }}/{{$permission['id']}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are you sure you want delete this permission?')">Delete</a>
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
                                @if(Auth::user()->can('view-permission') || Auth::user()->can('edit-permission') || Auth::user()->can('delete-permission'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                        </table>
                        <div align="right">@include('pagination.default', ['paginator' => $permissions])</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection