@extends('default')

@section('content')

<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2>Edit Driver</h2>
        <ol class="breadcrumb">
            <li>
                <a href="{{ url('/home') }}">Home</a>
            </li>
            <li>
                <a href="{{ url('/driver') }}">Drivers</a>
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
                    <h5>All form elements <small>With custom checbox and radion elements.</small></h5>
                    <div class="ibox-tools">
                        <a class="collapse-link">
                            <i class="fa fa-chevron-up"></i>
                        </a>
                    </div>
                </div>
                <div class="ibox-content">
                    <!-- Form -->
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('driver/update') }}" enctype="multipart/form-data" file="true">
                     {!! csrf_field() !!}

                     <div class="form-group">
                            <label class="col-sm-2 control-label">Profile Image</label>
                                  <div class="col-sm-10">
                                      @if($drivers['profile_image_path'] != null)
                                      <img src="{{$drivers['profile_image_path']}}" width="200" height="150">
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                     <div class="form-group
                            @if ($errors->has('email'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Email</label>
                                  <div class="col-sm-10">
                                      <input type="text" class="form-control" name="email" placeholder="Email" required="" value="{{ $drivers['email'] }}" disabled>
                                      @if ($errors->has('email'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('email') }}</strong>
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
                                 <input type="text" class="form-control" name="firstname" placeholder="FirstName" required="" value="{{ $drivers['firstname'] }}">
                                  @if ($errors->has('firstname'))
                                      <span class="help-block">
                                      <strong style="color:red;">{{ $errors->first('firstname') }}</strong>
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
                                        <input type="text" class="form-control" name="lastname" placeholder="LastName" required="" value="{{ $drivers['lastname'] }}">
                                        @if ($errors->has('lastname'))
                                            <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('lastname') }}</strong>
                                            </span>
                                        @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>


                        <div class="form-group
                            @if ($errors->has('gender'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Gender</label>
                                  <div class="col-sm-10">
                                      <select name="gender" class="form-control" required="">
                                        <option value="Male" @if($drivers['gender'] == 'Male') selected="" @endif>Male</option>
                                        <option value="Female" @if($drivers['gender'] == 'Female') selected="" @endif>Female</option>
                                      </select>
                                      {{-- <input type="text" class="form-control" name="gender" placeholder="Gender" required="" value="{{ $drivers['gender'] }}"> --}}
                                      @if ($errors->has('gender'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('gender') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('dob'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Date of Birth</label>
                                  <div class="col-sm-10">
                                       <input type="text" class="form-control" name="dob" placeholder="Date of Birth" required="" value="{{ $drivers['dob'] }}">
                                      @if ($errors->has('dob'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('dob') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('contact_no'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Contact No</label>
                                  <div class="col-sm-10">
                                       <input type="text" class="form-control" name="contact_no" placeholder="Contact_no" required="" value="{{ $drivers['contact_no'] }}">
                                      @if ($errors->has('contact_no'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('contact_no') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('address'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Address</label>
                                  <div class="col-sm-10">
                                       <textarea class="form-control" rows="4" name="address" placeholder="address" required="">{{ $drivers['address'] }}</textarea>
                                        @if ($errors->has('address'))
                                            <span class="help-block">
                                            <strong style="color:red;">{{ $errors->first('address') }}</strong>
                                            </span>
                                        @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('profile'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Profile Image</label>
                                  <div class="col-sm-10">
                                       <input type="file" class="form-control" name="profile" placeholder="Profile Image" value="{{ old('profile') }}">
                                      @if ($errors->has('profile'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('profile') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        <div class="form-group
                            @if ($errors->has('license'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">License</label>
                                  <div class="col-sm-10">
                                      @if(isset($drivers['license_image_path']))
                                      <img src="{{$drivers['license_image_path']}}" width="450" height="275">
                                      @endif
                                       <input type="file" class="form-control" name="license" placeholder="License" value="{{ old('license') }}">
                                      @if ($errors->has('license'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('license') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div>

                        {{-- <div class="form-group
                            @if ($errors->has('video'))
                               has-error
                           @endif">
                            <label class="col-sm-2 control-label">Video</label>
                                  <div class="col-sm-10">
                                       <input type="file" class="form-control" name="video" placeholder="Video" value="{{ old('video') }}">
                                      @if ($errors->has('video'))
                                          <span class="help-block">
                                          <strong style="color:red;">{{ $errors->first('video') }}</strong>
                                          </span>
                                      @endif
                                  </div>
                              </div>
                        <div class="hr-line-dashed"></div> --}}

                        <div class="form-group">
                        <input type="hidden" name="id" value = "{{$drivers['id']}}">
                            <div class="col-sm-4 col-sm-offset-2">
                                <button class="btn btn-white" type="submit" onClick="history.go(-1);return true;">Cancel</button>
                                <button class="btn btn-primary" type="submit">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection