@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Driver Transaction Lists</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Driver Transaction Lists</strong>
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
             <div class="ibox">
                 <div class="ibox-title">
                        <h5>Transaction Summary</h5>
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
                            <div class="col-lg-5">
                                <dl class="dl-horizontal">
                                    {{-- <dt>Last Payments :</dt> 
                                    <dd>
                                        @if(Auth::user()->can('view-payment-history'))
                                            <a href="/transaction_payment/view"><font color="red">{{$summaries['last_payment']}}</font></a>
                                        @else
                                            <font color="red">{{$summaries['last_payment']}}</font>
                                        @endif
                                    </dd> --}}
                                    <dt>Total Hours :</dt> <dd>{{$summaries['total_hours']}} Hours</dd>
                                    <dt>Total Penalties:</dt> <dd>$ {{number_format($summaries['total_penalties'], 2)}}</dd>
                                    <dt>Total Midnight:</dt> <dd>$ {{number_format($summaries['total_midnight'], 2)}}</dd>
                                </dl>
                            </div>
                            <div class="col-lg-7" id="cluster_info">
                                <dl class="dl-horizontal" >
                                    <dt>Total Received:</dt> <dd>$ {{number_format($summaries['total_income'], 2)}}</dd>
                                    <dt>Total Payout:</dt> <dd>$ {{number_format($summaries['total_payout'], 2)}}</dd>
                                    <dt>Net Income:</dt> <dd>$ {{number_format($summaries['net_income'], 2)}}</dd>
                                </dl>
                            </div>
                    </div>
                </div>
            </div>

            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Transaction List</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    {{-- <div class="row"> --}}
                        {{-- <div class="col-md-4" align="left">
                            <form  class="form-horizontal" role="form" method="GET" action="{{ url('driver-transactions') }}">
                            <div class="input-group"><input class="form-control" type="text" name="daterange" 
                            @if(app('request')->input('daterange'))
                                value= "{{app('request')->input('daterange')}}" 
                            @else
                                value = "01/01/2016 - 04/30/2016"
                            @endif
                            /> <span class="input-group-btn"> <button type="submit" class="btn btn-primary"> Filter
                                        </button> </span></div></form>
                        </div>
                        
                        <div class="col-md-8" align="right">
                            <a href="{{ URL::to('admin/driver/create') }}" class="btn btn-primary ">Make Payment</a>
                        </div> --}}

                        
                    {{-- </div> --}}
                    <div class="row">
                        {{-- <form  class="form-horizontal" role="form" method="GET" action="{{ url('driver-transactions') }}"> --}}
                            <div class="col-md-4" align="left">
                                <form  class="form-horizontal" role="form" method="GET" name="driverTrans" action="{{ url('driver-transactions') }}">
                                <label class="font-noraml">Date Range</label>
                                <div class="input-group">
                                    <input class="form-control"type="text" name="daterange" onchange="filter('driverTrans')"
                                        @if(app('request')->input('daterange'))
                                            value= "{{app('request')->input('daterange')}}" 
                                        @else
                                            value = "{{$this_month}}"
                                        @endif
                                    />
                                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    
                                </div>
                                </form>
                            </div>
                           {{--  <div class="col-md-8" align="right">
                                <form class="form-horizontal" role="form" method="GET" action="{{ url('transaction_payment') }}">
                                    <input class="form-control" type="hidden" name="payment_range" 
                                        @if(app('request')->input('daterange'))
                                            value= "{{app('request')->input('daterange')}}" 
                                        @else
                                            value = "{{$this_month}}"
                                        @endif
                                    /> --}}
                                 {{--    @if(Auth::user()->can('male-payment'))
                                        <input type="hidden" name="payment_amount" value="{{$summaries['total_payout']}}">
                                        <span class="input-group-btn"> <button type="submit" class="btn btn-primary">Make Payment</button></span>
                                    @endif --}}
                                {{-- </form> --}}
                            {{-- </div> --}}
                        {{-- </form> --}}
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Driver ID</th>
                                <th>Driver Name</th>
                                <th>Jobs</th>
                                <th>Total Hours</th>
                                <th>Midnight</th>
                                <th>No.Penalties</th>
                                <th>Penalties</th>
                                <th>Total Earns</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($jobs) >= 1)
                            @foreach($jobs as $job)
                            {{--  @if(Auth::user()->can('view-driver'))
                                <tr class="gradeX" onclick="window.location.href = '/driver/view/{{ $job['driver_id'] }}';">
                            @else --}}
                                <tr class="gradeX">
                            {{-- @endif --}}
                                    <td>{{ $job['driver_id'] }}</td>
                                    <td>
                                    @if(Auth::user()->can('view-driver'))
                                        <a href="{{ URL::to('driver/view') }}/{{ $job['driver_id'] }}">
                                            {{ $job['name']}}
                                        </a>
                                    @else
                                        {{ $job['name']}}
                                    @endif
                                    </td>
                                    <td>{{ $job['jobs']}}</td>
                                    <td> {{ $job['total_hours']}} </td>
                                    <td> {{ $job['midnight'] }} </td>
                                    <td>{{$job['num_penalties']}}</td>
                                    <td>$ {{ number_format($job['penalties'],2) }} </td>
                                    <td>$ {{ number_format($job['earns'],2) }} </td>
                                    <td>
                                        @if(Auth::user()->can('make-payment-to-driver'))
                                            @if($job['status'] != 1 && $job['earns'] > 0 )
                                                <a href="#" role="button" class="label label-danger" onclick="if(confirm('Are You Sure You Want To Make Payment?')) document.forms['makePayment{{$job['driver_id']}}'].submit(); return false; " formaction="{{ url('driver-transactions/pay_driver_weekly') }}">Pay Now</a>
                                            @endif
                                        @endif
                                        @if(Auth::user()->can('view-driver-transaction-detail'))
                                            <a href="#" role="button" class="label label-success" onclick="document.forms['view{{$job['driver_id']}}'].submit(); return false;" >View</a>
                                        @endif
                                    </td>
                                </tr>
                                <form  class="form-horizontal" role="form" method="POST" name="view{{$job['driver_id']}}" action="{{ url('driver-transactions/view_driver_transaction_details') }}">
                                        <input type="hidden" name="selectedDateRange" 
                                            @if(app('request')->input('daterange'))
                                                value= "{{app('request')->input('daterange')}}" 
                                            @else
                                                value = "{{$this_month}}"
                                            @endif
                                        />
                                        <input type="hidden" name="selectedDriverID" value="{{$job['driver_id']}}">
                                </form>
                                <form  class="form-horizontal" role="form" method="POST" name="makePayment{{$job['driver_id']}}" action="{{ url('driver-transactions/pay_driver_weekly') }}">
                                    <input type="hidden" name="selectedDateRange" 
                                        @if(app('request')->input('daterange'))
                                            value= "{{app('request')->input('daterange')}}" 
                                        @else
                                            value = "{{$this_month}}"
                                        @endif
                                    />
                                    <input type="hidden" name="selectedDriverID" value="{{$job['driver_id']}}">
                                </form>
                            @endforeach
                            @endif
                                
                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Driver ID</th>
                                <th>Driver Name</th>
                                <th>Jobs</th>
                                <th>Total Hours</th>
                                <th>Midnight</th>
                                <th>No.Penalties</th>
                                <th>Penalties</th>
                                <th>Total Earns</th>
                                <th>Action</th>
                            </tr>
                            </tfoot>
                        </table>
                        @if(app('request')->input('daterange'))
                        <div align="right">@include('pagination.default', ['paginator' => $all_drivers->appends(['daterange' => app('request')->input('daterange')])])</div>
                        @else
                            <div align="right">@include('pagination.default', ['paginator' => $all_drivers])</div>
                        @endif
                    </div>
                     <form  class="form-horizontal" role="form" method="POST" action="{{ url('driver-transactions/export') }}">
                        <input type="hidden" name="export_date" 
                        @if(app('request')->input('daterange'))
                            value="{{app('request')->input('daterange')}}"
                        @else
                            value="{{$this_month}}"
                        @endif
                            >
                        @if(Auth::user()->can('export-driver-transaction'))
                            <span class="input-group-btn" align="center"> <button type="submit" class="btn btn-primary">Export</button></span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection