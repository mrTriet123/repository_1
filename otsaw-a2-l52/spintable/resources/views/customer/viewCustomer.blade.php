@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View Customer</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('/customers') }}">Customers</a>
            </li>
            <li class="active">
                <strong>View</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row m-b-lg m-t-lg">
        <div class="col-md-4">
            <div class="profile-image">
                <img src="{{$customers['profile_image_path']}}" class="img-circle circle-border m-b-md" alt="profile">
            </div>
            <div class="profile-info">
                <div class="">
                    <div>
                        <h2 class="no-margins">
                            {{$customers['firstname']}} {{$customers['lastname']}}
                        </h2>
                        <h4>Customer</h4>
                        <h5>Address : </h5>
                        <small>
                            {{$customers['address']}}
                        </small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5">
            <table class="table small m-b-xs">
                <tbody>
                    <tr>
                        <td>
                            <strong>Email :</strong> {{$customers['email']}}
                        </td>
                        <td>
                            <strong>Gender :</strong> {{$customers['gender']}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Contact No :</strong> {{$customers['contact_no']}}
                        </td>
                        <td>
                            <strong>Date Of Birth :</strong> {{$customers['dob']}}
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <strong>Rate : </strong> {{$customers['rate']}} 
                        </td>
                        <td>
                            <strong>Home No :</strong> 
                            @if (is_null($customers['home_no']))
                                NA
                            @else
                                {{$customers['home_no']}}
                            @endif
                        </td>
                        
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-3">
            <small>Total Requested</small>
            <h2 class="no-margins">{{$customers['total_requests']}} </h2>
            <div id="sparkline1"></div>
        </div>
    </div>
    
    @if(Auth::user()->can('view-job'))
     <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>Jobs list</h5>
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
                        <form  class="form-horizontal" role="form" method="GET" name="customerViewFilter" action="{{ url('customers/view') }}/{{$customers['id']}}">
                            <div class="col-md-2">
                                    <div class="input-group">
                                        <label class="font-noraml">Status</label>
                                        <select name="job_status" class="form-control" onchange="filter('customerViewFilter')" >
                                            <option value="All"
                                                @if(app('request')->input('job_status') == 'All')
                                                    selected
                                                @endif
                                                >All
                                            </option>
                                             <option value="Accepted"
                                                @if(app('request')->input('job_status') == 'Accepted')
                                                    selected
                                                @endif
                                                >Accepted
                                            </option>
                                            <option value="Cancel"
                                                @if(app('request')->input('job_status') == 'Cancel')
                                                    selected
                                                @endif
                                                >Cancel
                                            </option>
                                            <option value="Completed"
                                                @if(app('request')->input('job_status') == 'Completed')
                                                    selected
                                                @endif
                                                >Completed
                                            </option>
                                            <option value="Pending"
                                                @if(app('request')->input('job_status') == 'Pending')
                                                    selected
                                                @endif
                                                >Pending
                                            </option>
                                            
                                        </select>
                                    </div>
                            </div>
                            <div class="col-md-4">
                                {{-- <a href="{{ URL::to('admin/driver/create') }}" class="btn btn-primary ">Create a new customer</a> --}}
                                    <label class="font-noraml">Date Range</label>
                                    <div class="input-group">
                                        <input class="form-control" type="text" name="daterange" onchange="filter('customerViewFilter')"
                                            @if(app('request')->input('daterange'))
                                                value= "{{app('request')->input('daterange')}}" 
                                            @else
                                                value = "{{$date}}" 
                                            @endif
                                        /><span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    
                                    </div>
                            </div>
                            <div class="col-md-2">
                            </div>
                        
                            <div class="col-md-4">
                                <label class="font-noraml">Search Job ID</label>
                                <div class="input-group"><input type="text" name="searchValue" placeholder="Search Jobs" class="form-control"
                                @if(app('request')->input('daterange'))
                                        value= "{{app('request')->input('searchValue')}}"
                                @endif
                                > <span class="input-group-btn"> <button type="submit" class="btn btn-primary"> Search
                                            </button> </span></div>
                            </div>
                        </form>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-hover dataTables-example" >
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle No</th>
                                <th>Est Hours</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Driver</th>
                                <th>Total Amount</th>
                            </tr>
                            </thead>
                            <tbody>
                           @foreach($jobs as $job)
                                <tr class="gradeX">
                                    <td>
                                    <a href="{{ URL::to('jobs/view') }}/{{$job['id']}}">
                                        {{$job['id']}}
                                    </a>
                                    
                                    </td>
                                    <td>{{$job['vehicle_no']}}</td>
                                    <td>{{$job['est_hour']}}</td>
                                    <td>
                                    @if (is_null($job['total_hours']))
                                            NA
                                    @else
                                        {{$job['total_hours']}}
                                    @endif
                                    
                                    </td>
                                    <td>
                                    @if($job['status'] == 'Pending')
                                        <span class="label label-danger">{{$job['status']}}</span>
                                    @endif
                                    @if($job['status'] == 'Accepted')
                                        <span class="label label-warning">{{$job['status']}}</span>
                                    @endif
                                    @if($job['status'] == 'Completed')
                                        <span class="label label-primary">{{$job['status']}}</span>
                                    @endif
                                    @if($job['status'] == 'Cancel')
                                        <span class="label label-info">{{$job['status']}}</span>
                                    @endif
                                    </td>
                                    <td>
                                    @if (is_null($job['driver']))
                                        NA
                                    @else
                                        @if(Auth::user()->can('view-driver'))
                                            <a href="{{ URL::to('driver/view') }}/{{ $job['driver']['id'] }}">
                                                {{ $job['driver']['firstname']}}
                                            </a>
                                        @else
                                            {{ $job['driver']['firstname']}}
                                        @endif
                                    @endif
                                    </td>
                                    <td>
                                    @if (is_null($job['total_amount']))
                                        NA
                                    @else
                                        {{$job['total_amount']}}
                                    @endif
                                    </td>
                                </tr>
                            @endforeach

                            </tbody>
                            <tfoot>
                            <tr>
                                <th>ID</th>
                                <th>Vehicle No</th>
                                <th>Est Hours</th>
                                <th>Total Hours</th>
                                <th>Status</th>
                                <th>Driver</th>
                                <th>Total Amount</th>
                            </tr>
                            </tfoot>
                        </table>
                        <div align="right">@include('pagination.default', ['paginator' => $all_jobs])</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <div class="row">
        @if(Auth::user()->can('view-customer-remarks'))
            <div class="col-lg-6">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Remark History</h5>
                    </div>
                    <div class="ibox-content">
                        <div id="" style="overflow:scroll; height: 150px;">
                            <div class="col-lg-12">
                            @foreach($remarks as $remark)
                                <div class="ibox">
                                    <div class="ibox-title">
                                        <h4>Remark On {{$remark->created_at}} </h4>
                                    </div>
                                    <div class="ibox-content">
                                        {{$remark->remark}}
                                    </div>
                                </div>
                            @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        @if(Auth::user()->can('create-customer-remarks'))
            <div class="col-lg-5">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Remark</h5>
                    </div>
                    <div class="ibox-content">
                            <form  class="form-horizontal" role="form" method="POST" action="{{ url('customers/remarks') }}">
                                <input type="hidden" name="customer_id" value="{{$customers['id']}}">
                                <textarea class="form-control" rows="5" id="remark" name="customer_remark"></textarea>
                                <div class="m-t-sm" align="right">
                                    <div class="btn-group">
                                        <button type="submit" class="btn btn-primary btn-sm">Submit</button>
                                    </div>
                                </div>
                            </form>
                    </div>
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
