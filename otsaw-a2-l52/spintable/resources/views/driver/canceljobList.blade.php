@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Drivers Cancel List</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Drivers Cancel Lists</strong>
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
                    <h5>Driver Cancel List</h5>
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
                        <div class="col-md-4">
                            <form  class="form-horizontal" role="form" method="GET" name="canceljobList" action="{{ url('driver/cancel_jobs') }}">
                                <label class="font-noraml">Date Range</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="daterange" onchange="filter('canceljobList')" 
                                        @if(app('request')->input('daterange'))
                                            value= "{{app('request')->input('daterange')}}" 
                                        @else
                                            value = "{{$date}}" 
                                        @endif
                                    />
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Job ID</th>
                                <th>Accepted At</th>
                                <th>Driver</th>
                                <th>Cancel At</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($jobs as $job)
                                <tr class="gradeX">
                                @if(Auth::user()->can('view-job'))
                                    <td><a href="{{ URL::to('jobs/view') }}/{{ $job['id']}}"> {{$job['id']}} </a></td>
                                @else
                                    <td>{{$job['id']}}</td>
                                @endif
                                    <td>{{$job['accepted_at']}}</td>
                                @if(Auth::user()->can('view-driver'))
                                    <td> <a href="{{ URL::to('driver/view') }}/{{ $job['driver_id']}}">{{$job['driver_name']}}</a></td>
                                @else
                                    <td>{{$job['driver_name']}}</td>
                                @endif
                                    <td>{{$job['cancel_at']}}</td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Job ID</th>
                                <th>Accepted At</th>
                                <th>Driver</th>
                                <th>Cancel At</th>
                            </tr>
                            </tfoot>
                        </table>
                        @if(app('request')->input('daterange'))
                        <div align="right">@include('pagination.default', ['paginator' => $all_jobs->appends(['daterange' => app('request')->input('daterange'),])])</div>
                        @else
                            <div align="right">@include('pagination.default', ['paginator' => $all_jobs])</div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection