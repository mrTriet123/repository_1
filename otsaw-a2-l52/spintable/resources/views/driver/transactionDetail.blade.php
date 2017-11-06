@extends('default')

@section('content')

@include('layouts.default.breadcrumb')

<div class="wrapper wrapper-content animated fadeInRight">

   <div class="row">
            <div class="col-lg-12">
                {{-- <div class="wrapper wrapper-content animated fadeInUp"> --}}
                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        
                                        <h2>Driver Details</h2>
                                    </div>
                                    <dl class="dl-horizontal">
                                        <dt>Last Payout:</dt> <dd></dd>
                                    </dl>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-5">
                                    <dl class="dl-horizontal">

                                        <dt>Driver ID:</dt> <dd></dd>
                                        <dt>Driver Name:</dt> <dd></dd>
                                        <dt>Jobs:</dt> <dd></dd>
                                        <dt>Total Hours:</dt> <dd></dd>
                                        
                                    </dl>
                                </div>
                                <div class="col-lg-7" id="cluster_info">
                                    <dl class="dl-horizontal" >

                                        <dt>Midnight:</dt> <dd></dd>
                                        <dt>Penalties:</dt> 
                                        <dd>
                                      {{--   @if(is_null($job['dropoff_address']))
                                            NA
                                        @else
                                            {{$job['dropoff_address']}}
                                        @endif --}}
                                        </dd>
                                        <dt>Total Earns:</dt> 
                                        <dd>
                                       {{--  @if(is_null($job['request_for']))
                                            NA
                                        @else
                                            {{$job['request_for']}}
                                        @endif --}}
                                        </dd>
                                        <dt>Rate:</dt> 
                                        <dd>
                                       {{--  @if(is_null($job['total_hours']))
                                             NA
                                        @else
                                            {{$job['total_hours']}}
                                        @endif --}}
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                         {{--    <div class="row">
                                <div class="col-lg-12">
                                    <dl class="dl-horizontal">
                                        <dt>Completed:</dt>
                                        <dd>
                                            <div class="progress progress-striped active m-b-sm">
                                                <div style="width: 60%;" class="progress-bar"></div>
                                            </div>
                                            <small>Project completed in <strong>60%</strong>. Remaining close the project, sign a contract and invoice.</small>
                                        </dd>
                                    </dl>
                                </div>
                            </div> --}}
                         
                        </div>
                    </div>
                     <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        
                                        <h2>Completed Jobs</h2>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row"> --}}
                                {{-- <div class="col-lg-5">
                                    <dl class="dl-horizontal">
                                        <dt>Total Hours:</dt> <dd></dd>
                                        <dt>Gender:</dt> <dd></dd>
                                        <dt>Home No:</dt> 
                                        <dd>
                                        @if(is_null($customers['home_no']))
                                             NA
                                        @else
                                            {{$customers['home_no']}}
                                        @endif
                                        </dd>
                                        <dt>Date Of Birthday:</dt><dd></dd>
                                    </dl>
                                </div> --}}
                                {{-- <div class="col-lg-7" id="cluster_info">
                                    <dl class="dl-horizontal" >
                                        
                                        <dt>Registration No:</dt> <dd></dd>
                                        <dt>Type:</dt> <dd></dd>
                                        <dt>Year:</dt> <dd></dd>
                                        <dt>Brand:</dt> <dd></dd>
                                        <dt>Model:</dt> <dd></dd>
                                        <dt>Address:</dt><dd></dd>
                                    </dl>
                                </div> --}}
                            {{-- </div> --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th>Driver ID</th>
                                            <th>Driver Name</th>
                                            <th>Jobs</th>
                                            <th>Total Hours</th>
                                            <th>Midnight</th>
                                            <th>Penalties</th>
                                            <th>Total Earns</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Driver ID</th>
                                        <th>Driver Name</th>
                                        <th>Jobs</th>
                                        <th>Total Hours</th>
                                        <th>Midnight</th>
                                        <th>Penalties</th>
                                        <th>Total Earns</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                {{-- <div align="right">@include('pagination.default', ['paginator' => $all_drivers])</div> --}}
                            </div>
                         
                        </div>
                    </div>

                    <div class="ibox">
                        <div class="ibox-content">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="m-b-md">
                                        
                                        <h2>Penalties</h2>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row"> --}}
                                {{-- <div class="col-lg-5">
                                    <dl class="dl-horizontal">
                                        <dt>Total Hours:</dt> <dd></dd>
                                        <dt>Gender:</dt> <dd></dd>
                                        <dt>Home No:</dt> 
                                        <dd>
                                        @if(is_null($customers['home_no']))
                                             NA
                                        @else
                                            {{$customers['home_no']}}
                                        @endif
                                        </dd>
                                        <dt>Date Of Birthday:</dt><dd></dd>
                                    </dl>
                                </div> --}}
                                {{-- <div class="col-lg-7" id="cluster_info">
                                    <dl class="dl-horizontal" >
                                        
                                        <dt>Registration No:</dt> <dd></dd>
                                        <dt>Type:</dt> <dd></dd>
                                        <dt>Year:</dt> <dd></dd>
                                        <dt>Brand:</dt> <dd></dd>
                                        <dt>Model:</dt> <dd></dd>
                                        <dt>Address:</dt><dd></dd>
                                    </dl>
                                </div> --}}
                            {{-- </div> --}}
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered table-hover dataTables-example" >
                                    <thead>
                                        <tr>
                                            <th>Job ID</th>
                                            <th>Reason</th>
                                            <th>Date</th>
                                            <th>Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    

                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th>Job ID</th>
                                        <th>Reason</th>
                                        <th>Date</th>
                                        <th>Amount</th>
                                    </tr>
                                    </tfoot>
                                </table>
                                {{-- <div align="right">@include('pagination.default', ['paginator' => $all_drivers])</div> --}}
                            </div>
                         
                        </div>
                    </div>
                
            </div>
            
        </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection