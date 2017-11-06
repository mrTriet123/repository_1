@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Create Role</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('/role/role-list') }}">Roles</a>
            </li>
            <li class="active">
                <strong>Create</strong>
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
                    <form  class="form-horizontal" role="form" method="POST" action="{{ url('role/create') }}">
                     {!! csrf_field() !!}

                        <div class="form-group
                        @if ($errors->has('name'))
                            has-error
                        @endif">
                            <label class="col-sm-2 control-label">Name</label>

                            <div class="col-sm-10">
                                 <input type="text" class="form-control" name="name" placeholder="Name" required="" value="{{ old('name') }}">
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
                                       <input type="text" class="form-control" name="display_name" placeholder="Display Name" required="" value="{{ old('display_name') }}">
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
                                       <textarea class="form-control" rows="4" name="description" placeholder="Description" required="">{{ old('description') }}</textarea>
                                       @if ($errors->has('description'))
                                           <span class="help-block">
                                           <i style="color:red;">{{ $errors->first('description') }}</i>
                                           </span>
                                       @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('permissions'))
                               has-error
                            @endif">
                            <label class="col-sm-2 control-label">User Role</label>
                            <div class="col-sm-10">
                                @foreach($permissions as $permission)
                                    <div class="col-md-4">
                                        <div class="checkbox">
                                            <label><input type="checkbox" name="permissions[]" class="permissions" value="{{$permission->id}}">{{$permission->display_name}}</label>
                                        </div>
                                    </div>
                                @endforeach
                                @if ($errors->has('permissions'))
                                    <span class="help-block">
                                       <i style="color:red;">{{ $errors->first('permissions') }}</i>
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>
                        <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="submit" onClick="history.go(-1);return true;">Cancel</button>
                                <button class="btn btn-primary" type="submit">Save changes</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
