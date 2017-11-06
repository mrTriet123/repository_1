<div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
        <li class="nav-header">
            <div class="dropdown profile-element">
                     <span>
                         <img alt="image" class="img-circle" src="{{ asset('assets/img/profile_small.jpg') }}" />
                        <?php //$image = Helpers::getUserImageType(Auth::user()->id); ?>
                            {{--<img alt="image" class="img-circle" src="{{ $image['profile_image'] }}" style="width:42px;height:42px;" title="FIV"/>--}}
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> 
                            <span class="block m-t-xs"> 
                                <strong class="font-bold">{{Auth::user()->firstname}}</strong>
                            </span> 
                            <span class="text-muted text-xs block">
                                 @if(Auth::user()->hasRole('admin')) Administrator @endif 
                                 @if(Auth::user()->hasRole('driver')) Driver @endif
                                 @if(Auth::user()->hasRole('hr')) Human Resource @endif
                                 @if(Auth::user()->hasRole('finance')) Finance @endif
                                 @if(Auth::user()->hasRole('customer_service')) Customer Service @endif
                                 @if(Auth::user()->hasRole('operation')) Operation @endif
                                 @if(Auth::user()->hasRole('marketing')) Marketing @endif
                                <b class="caret"></b>
                            </span> 
                        </span> 
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
            </div>
            <div class="logo-element">
                <img alt="image" src="{{ asset('assets/img/fiv/logo.png') }}" style="width:32px;height:32px;" title="FIV - Get your own private chauffeur"/>
            </div>
        </li>
        {{-- get url path --}}
        <?php $path = Route::getCurrentRoute()->getPath(); ?>
        <li {{{ (Request::is('home') ? 'class=active' : '') }}}>
            <a href="{{ url('/home') }}"><i class="fa fa-th-large"></i> <span class="nav-label">Dashboard</span></a>
        </li>
        @if(Auth::user()->hasRole('driver'))
        <li {{{ (Request::is('driver_profile') ? 'class=active' : '') }}}>
            <a href="{{ url('/driver_profile') }}"><i class="fa fa-user"></i> <span class="nav-label">Profile</span></a>
        </li>
        @else
        <li {{{ (Request::is('user_profile') ? 'class=active' : '') }}}>
            <a href="{{ url('/user_profile') }}"><i class="fa fa-user"></i> <span class="nav-label">Profile</span></a>
        </li>
        @endif
        @if(Auth::user()->can('user-list'))
        <li 
        <?php if (starts_with($path, 'user/')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('user/user-list') }}"><i class="fa fa-user-plus"></i> <span class="nav-label">User</span></a>
        </li>
        @endif
        @if(Auth::user()->can('role-list'))
        <li
        <?php if (starts_with($path, 'role/')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('role/role-list') }}"><i class="fa fa-cogs"></i> <span class="nav-label">Role</span></a>
        </li>
        @endif
        @if(Auth::user()->can('permission-list'))
        <li
        <?php if (starts_with($path, 'permissions/')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('permissions/permissions-list') }}"><i class="fa fa-cog"></i> <span class="nav-label">Permissions</span></a>
        </li>
        @endif
        @if(Auth::user()->can('driver-list') || Auth::user()->can('driver-cancel-list'))
        <li {{{ (Request::is('driver') ? 'class=active' : '') }}}
        <?php if (starts_with($path, 'driver/')){ echo 'class=active'; } ?>
        >
            <a href="#"><i class="fa fa-car"></i> <span class="nav-label">Drivers</span><span class="fa arrow"></span></a>
            <ul class="nav nav-second-level collapse">
                @if(Auth::user()->can('driver-list'))
                    <li {{{ (Request::is('driver') ? 'class=active' : '') }}}><a href="{{ url('driver') }}"><span class="nav-label">Driver Lists</span></a></li>
                @endif
                @if(Auth::user()->can('driver-cancel-list'))
                    <li {{{ (Request::is('driver/cancel_jobs') ? 'class=active' : '') }}}>
                        <a href="{{ url('driver/cancel_jobs') }}"><span class="nav-label">Driver Cancel Jobs</span></a>
                    </li>
                @endif
            </ul>
        </li>
        @endif
        @if(Auth::user()->can('customer-list'))
        <li
        <?php if (starts_with($path, 'customers')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('customers') }}"><i class="fa fa-users"></i> <span class="nav-label">Customer</span></a>
        </li>
        @endif
        @if(Auth::user()->can('view-job'))
        <li
        <?php if (starts_with($path, 'jobs')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('jobs') }}"><i class="fa fa-suitcase"></i> <span class="nav-label">Jobs</span></a>
        </li>
        @endif
        @if(Auth::user()->can('driver-transaction'))
        <li {{{ (Request::is('driver-transactions') ? 'class=active' : '') }}}
        <?php if (starts_with($path, 'driver-transactions/')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('driver-transactions') }}"><i class="fa fa-list-ol"></i> <span class="nav-label"> Driver Transaction</span></a>
        </li>
        @endif
        @if(Auth::user()->can('customer-transaction'))
        <li {{{ (Request::is('customer-transactions') ? 'class=active' : '') }}}>
            <a href="{{ url('customer-transactions') }}"><i class="fa fa-list-alt"></i> <span class="nav-label"> Customer Transaction</span></a>
        </li>
        @endif
        @if(Auth::user()->can('promotion-list'))
        <li
        <?php if (starts_with($path, 'promotions')){ echo 'class=active'; } ?>
        >
            <a href="{{ url('promotions') }}"><i class="fa fa-tags"></i> <span class="nav-label">Promotion</span></a>
        </li>
        @endif


    </ul>
</div>