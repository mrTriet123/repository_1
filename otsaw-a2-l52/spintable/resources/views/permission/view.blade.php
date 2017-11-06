@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View Permission</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('permissions/permissions-list') }}">Permissions</a>
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
    
    <div class="row">
        <div class="col-lg-12">

        <!-- Notification -->
        @include('layouts.default.notification')
        <!-- Notification -->


            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>All form elements <small>With custom checbox and radion elements.</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Form -->
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('promotions/create') }}" enctype="multipart/form-data" file="true">
                     {!! csrf_field() !!}

                        <div class="form-group">
                            <label class="col-sm-2" align="right">Name</label>

                            <div class="col-sm-10">
                                 {{$permission['name']}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>


                        <div class="form-group">
                            <label class="col-sm-2" align="right">Display Name</label>
                            <div class="col-sm-10">
                                 {{$permission['display_name']}}
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>


                        <div class="form-group">
                            <label class="col-sm-2" align="right">Description</label>
                                  <div class="col-sm-10">
                                      {{$permission['description']}}
                                  </div>
                        </div>
                     
                     
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection