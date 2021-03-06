@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Edit Permissions</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('permissions/permissions-list') }}">Permissions</a>
            </li>
            <li class="active">
                <strong>Edit</strong>
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
                    <h5>Role Permission</h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Form -->
                    <form  class="form-horizontal" role="form" method="POST" action="{{ url('permissions/updates') }}">
                     {!! csrf_field() !!}
                     <input type="hidden" name="id" value="{{$permission['id']}}">
                     <div class="form-group
                         @if ($errors->has('name'))
                             has-error
                         @endif">

                        <label class="col-sm-2 control-label">Name</label>

                        <div class="col-sm-10">

                            @if (old('name') == "")
                                <input type="text" class="form-control" name="name" placeholder="Name" required="" value="{{ $permission['name']}}">
                            @endif

                            @if (old('name') != "" )
                                <input type="text" class="form-control" name="name" placeholder="Name" required="" value="{{ old('name')}}">
                            @endif

                            @if ($errors->has('name'))
                                <span class="help-block">
                                    <i style="color:red;">{{ $errors->first('name') }}</i>
                                </span>
                            @endif

                         </div>
                     </div>

                     <div class="hr-line-dashed"></div>

                     <div class="form-group
                        @if ($errors->has('display_name'))
                            has-error
                        @endif">

                        <label class="col-sm-2 control-label">Display Name</label>

                        <div class="col-sm-10">
                            @if (old('display_name')  == "")
                                <input type="text" class="form-control" name="display_name" placeholder="Display Name" required="" value="{{ $permission['display_name'] }}">
                            @endif
                            @if (old('display_name') != "" )
                                <input type="text" class="form-control" name="display_name" placeholder="Display Name" required="" value="{{ old('display_name') }}">
                            @endif
                            @if ($errors->has('display_name'))
                                <span class="help-block">
                                    <i style="color:red;">{{ $errors->first('display_name') }}</i>
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group
                        @if ($errors->has('description'))
                           has-error
                        @endif">
                            <label class="col-sm-2 control-label">Description</label>

                            <div class="col-sm-10">

                                @if (old('description')  == "")
                                    <textarea class="form-control" rows="4" name="description" placeholder="Description" required="">{{ $permission['description']}}</textarea>
                                @endif
                                @if (old('description')  != "")
                                    <textarea class="form-control" rows="4" name="description" placeholder="Description" required="">{{ old('description')}}</textarea>
                                @endif
                                @if ($errors->has('description'))
                                    <span class="help-block">
                                        <i style="color:red;">{{ $errors->first('description') }}</i>
                                    </span>
                                @endif

                            </div>
                    </div>

                    <div class="hr-line-dashed"></div>

                    <div class="form-group">
                        <div class="col-sm-4 col-sm-offset-2">
                            <button class="btn btn-white" type="submit" onClick="history.go(-1);return true;">Cancel</button>
                            <button class="btn btn-primary" type="submit">Update changes</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
