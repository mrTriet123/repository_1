@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>View Profile</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li class="active">
                <strong>View Profile</strong>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">

    </div>
</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row m-b-lg m-t-lg">
    <form class="form-horizontal" role="form" method="POST" action="{{ url('users/update_profile') }}" enctype="multipart/form-data" file="true">
      {!! csrf_field() !!}
        <div class="col-md-3">
          <div class="text-center">
            @if(isset($users['profile_image_path']) && $users['profile_image_path'] != null)
              <img src="{{$users['profile_image_path']}}" class="avatar img-circle" alt="avatar" width="150px" height="150px">
            @else
              <img src="/assets/img/default.png" class="avatar img-circle" alt="avatar" width="150px" height="150px">
            @endif
            <h6>Upload photo coming soon...</h6>
            {{-- <h6>Upload a different photo...</h6> --}}
            {{-- <input type="file" class="form-control" name="profile_image"> --}}
          </div>
        </div>
      
      <!-- edit form column -->
        <div class="col-md-9 personal-info">
          @include('layouts.default.notification')

          <div class="panel blank-panel">
            <div class="panel-heading">
              <div class="panel-options">
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#tab-1" data-toggle="tab">Personal info</a></li>
                  <li class=""><a href="#tab-2" data-toggle="tab">Change password</a></li>
                </ul>
              </div>
            </div>

            <div class="panel-body">
              <div class="tab-content">
                <div class="tab-pane active" id="tab-1">

                  <div class="form-group">
                    <label class="col-lg-3 control-label">Email:</label>
                    <div class="col-lg-8">
                      <input class="form-control" type="text" value="{{$users['email']}}" disabled>
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-lg-3 control-label">First name:</label>
                    <div class="col-lg-8">
                      <input class="form-control" type="text" name="firstname" required value="{{$users['firstname']}}">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-lg-3 control-label">Last name:</label>
                    <div class="col-lg-8">
                      <input class="form-control" type="text" name="lastname" value="{{$users['lastname']}}">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-8">
                      <input type="submit" class="btn btn-primary" value="Save Changes">
                      <span></span>
                      <input type="reset" class="btn btn-default" value="Cancel">
                    </div>
                  </div>
                </div> 
                {{-- end panel 1 --}}
                <div class="tab-pane" id="tab-2">
                  <div class="form-group">
                    <label class="col-md-3 control-label">Old Password:</label>
                    <div class="col-md-8">
                      <input class="form-control" type="password" name="oldpassword" placeholder="Current Password">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">New Password:</label>
                    <div class="col-md-8">
                      <input class="form-control" type="password" name="newpassword" placeholder="New Password">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label">Confirm New password:</label>
                    <div class="col-md-8">
                      <input class="form-control" type="password" name="confirmnewpassword" placeholder="Confirm New Password">
                    </div>
                  </div>

                  <div class="form-group">
                    <label class="col-md-3 control-label"></label>
                    <div class="col-md-8">
                      <input type="submit" class="btn btn-primary" value="Save Changes">
                      <span></span>
                      <input type="reset" class="btn btn-default" value="Cancel">
                    </div>
                  </div>
                </div>
                {{-- end panel 2 --}}
            </div>
          </div>
        </div>
      </div>
    </form>
   </div>
</div>
@endsection
