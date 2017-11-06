@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Customers List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Customers</strong>
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
                    <h5>Customer list</h5>
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
                    <form  class="form-horizontal" role="form" method="GET" name="searchCustomer" action="{{ url('customers') }}">
                        <div class="col-md-4">
                            <label class="font-noraml">Date Range</label>
                            <div class="input-group">
                                <input class="form-control" type="text" name="daterange" onchange="filter('searchCustomer')" 
                                    @if(app('request')->input('daterange'))
                                        value= "{{app('request')->input('daterange')}}" 
                                    @endif
                                />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                        <div class="col-md-4" align="right">
                        <label class="font-noraml"> </label>
                            {{-- <form  class="form-horizontal" role="form" method="GET" action="{{ url('customers') }}"> --}}
                            <div class="input-group"><input type="text" name="searchValue" placeholder="Search Customer" class="form-control"
                            @if(app('request')->input('searchValue'))
                                value= "{{app('request')->input('searchValue')}}"
                            @endif
                            > <span class="input-group-btn"> <button type="submit" class="btn btn-primary"> Search
                                        </button> </span></div>
                                        {{-- </form> --}}
                        </div>
                    </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Email</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Created At</th>
                                @if(Auth::user()->can('view-customer'))
                                    <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($customers as $customer)
                                <tr class="gradeX">
                                    <td>{{$customer->email}}</td>
                                    <td>{{$customer->firstname}}</td>
                                    <td>{{$customer->lastname}}</td>
                                    <td>{{$customer->created_at}}</td>
                                    @if(Auth::user()->can('view-customer'))
                                    <td class="text-right">
                                            <a href="{{ URL::to('customers/view') }}/{{$customer->customer_id}}" role="button" class="btn-white btn btn-xs">View</a>
                                        @if(Auth::user()->can('edit-customer'))
                                            <a href="{{ URL::to('customers/edit') }}/{{$customer->customer_id}}" role="button" class="btn-white btn btn-xs">Edit</a>
                                        @endif
                                        @if(Auth::user()->can('delete-customer'))
                                            <a href="{{ URL::to('customers/delete') }}/{{$customer->id}}" role="button" class="btn-white btn btn-xs" onclick="return confirm('Are you sure you want delete this user?')">Delete</a>
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
                                @if(Auth::user()->can('view-customer'))
                                <th class="text-right">Action</th>
                                @endif
                            </tr>
                            </tfoot>
                        </table>
                        @if(app('request')->input('daterange'))
                            <div align="right">@include('pagination.default', ['paginator' => $customers->appends(['daterange' => app('request')->input('daterange'), 'searchValue' => app('request')->input('searchValue')])])</div>
                        @else
                            <div align="right">@include('pagination.default', ['paginator' => $customers])</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection