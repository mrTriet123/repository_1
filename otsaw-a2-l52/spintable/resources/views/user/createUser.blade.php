@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Create User</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('/user/user-list') }}">Users</a>
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
                    <form  class="form-horizontal" role="form" method="POST" action="{{ url('user/create') }}">
                     {!! csrf_field() !!}

                        <div class="form-group
                        @if ($errors->has('email'))
                            has-error
                        @endif">
                            <label class="col-sm-2 control-label">Email</label>

                            <div class="col-sm-10">
                                 <input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ old('email') }}">
                                    @if ($errors->has('email'))
                                        <span class="help-block">
                                        <i style="color:red;">{{ $errors->first('email') }}</i>
                                        </span>
                                    @endif
                            </div>
                        </div>
                        <div class="hr-line-dashed"></div>


                        <div class="form-group
                        @if ($errors->has('firstname'))
                            has-error
                        @endif">
                            <label class="col-sm-2 control-label">FirstName</label>
                                  <div class="col-sm-10">
                                       <input type="text" class="form-control" name="firstname" placeholder="FirstName" required="" value="{{ old('firstname') }}">
                                       @if ($errors->has('firstname'))
                                           <span class="help-block">
                                           <i style="color:red;">{{ $errors->first('firstname') }}</i>
                                           </span>
                                       @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>


                        <div class="form-group
                            @if ($errors->has('lastname'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">LastName</label>
                                  <div class="col-sm-10">
                                       <input type="text" class="form-control" name="lastname" placeholder="LastName" required="" value="{{ old('lastname') }}">
                                       @if ($errors->has('lastname'))
                                           <span class="help-block">
                                           <i style="color:red;">{{ $errors->first('lastname') }}</i>
                                           </span>
                                       @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                         @if ($errors->has('role'))
                           has-error
                         @endif">
                            <label class="col-sm-2 control-label">User Role</label>
                                  <div class="col-sm-10">
                                       <select class="form-control" id="role" name="role">
                                           <option value=" ">Select Role</option>
                                           @foreach($roles as $role)
                                                   <option value="{{$role->id}}" @if(old('role') == $role->id) selected @endif >
                                                    {{$role->display_name}}
                                                   </option>
                                           @endforeach
                                       </select>
                                       @if ($errors->has('role'))
                                           <span class="help-block">
                                           <i style="color:red;">{{ $errors->first('role') }}</i>
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