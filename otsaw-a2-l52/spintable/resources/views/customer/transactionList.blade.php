@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Customer Transaction Lists</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>Customer Transaction Lists</strong>
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
                    <h5>Customer Transaction List</h5>
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
                        <form  class="form-horizontal" role="form" method="GET" name="customerTransaction" action="{{ url('customer-transactions') }}">
                            <div class="col-md-2">
                             <label class="font-noraml">Status</label>
                                <div class="input-group">
                                    <select name="transaction_status" class="form-control" onchange="filter('customerTransaction')">
                                        <option value="all"
                                            @if(app('request')->input('transaction_status') == 'all')
                                                selected
                                            @endif
                                            >All
                                        </option>
                                        <option value="success"
                                            @if(app('request')->input('transaction_status') == 'success')
                                                selected
                                            @endif
                                            >Success
                                        </option>
                                        <option value="fail"
                                            @if(app('request')->input('transaction_status') == 'fail')
                                                selected
                                            @endif
                                            >Fail
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                {{-- <form  class="form-horizontal" role="form" method="GET" action="{{ url('customer-transactions') }}"> --}}
                                <label class="font-noraml">Date Range</label>
                                <div class="input-group">
                                    <input class="form-control" type="text" name="daterange" onchange="filter('customerTransaction')"
                                        @if(app('request')->input('daterange'))
                                            value= "{{app('request')->input('daterange')}}" 
                                        @else
                                            value = "{{$this_month}}"
                                        @endif
                                    /> <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    
                                </div>
                                {{-- </form> --}}
                            </div>
                            
                            <div class="col-md-6" align="right">
                                
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Transaction ID</th>
                                <th>Transaction Date</th>
                                <th>Amount</th>

                                <th>Status</th>
                                {{-- <th>Transaction Success</th>
                                <th>Transaction Fail</th> --}}
                            </tr>
                            </thead>
                            <tbody>
                            @if (count($transactions) >= 1)
                            @foreach($transactions as $transaction)
                                <tr class="gradeX">
                                   {{--  <td>{{ $job['customer_id'] }}</td>
                                    <td>
                                        <a href="{{ URL::to('customer/view') }}/{{ $job['customer_id'] }}">
                                            {{ $job['name']}}
                                        </a>
                                    </td> --}}
                                    <td>{{ $transaction['customer_id']}}</td>
                                    <td> 
                                    @if(Auth::user()->can('view-customer'))
                                        <a href="{{ URL::to('customers/view') }}/{{ $transaction['customer_id'] }}">
                                            {{ $transaction['customer_name']}}
                                        </a>
                                    @else
                                        {{ $transaction['customer_name']}}
                                    @endif
                                    </td>
                                    <td> {{ $transaction['email'] }} </td>
                                    <td> {{ $transaction['transaction_id'] }} </td>
                                    <td> {{ $transaction['transaction_date'] }} </td>
                                    <td>$ {{ number_format($transaction['amount'], 2) }} </td>
                                    <td> {{ $transaction['status'] }} </td>
                                </tr>
                            @endforeach
                            @endif

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>Customer ID</th>
                                <th>Customer Name</th>
                                <th>Email</th>
                                <th>Transaction ID</th>
                                 <th>Transaction Date</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                            </tfoot>
                        </table>
                        @if(app('request')->input('daterange'))
                        <div align="right">@include('pagination.default', ['paginator' => $allResult->appends(['daterange' => app('request')->input('daterange'), 'transaction_status' => app('request')->input('transaction_status')])])</div>
                        @else
                            <div align="right">@include('pagination.default', ['paginator' => $allResult])</div>
                        @endif
                    </div>

                    <form  class="form-horizontal" role="form" method="GET" action="{{ url('customer-transactions/export') }}">
                        <input type="hidden" name="export_date" 
                        @if(app('request')->input('daterange'))
                            value="{{app('request')->input('daterange')}}"
                        @else
                            value="{{$this_month}}"
                        @endif
                            >
                        <input type="hidden" name="export_status" 
                        @if(app('request')->input('daterange'))
                            value="{{app('request')->input('transaction_status')}}"
                        @else
                            value="all"
                        @endif
                            >
                        @if(Auth::user()->can('export-customer-transaction'))
                            <span class="input-group-btn" align="center"> <button type="submit" class="btn btn-primary">Export</button></span>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection