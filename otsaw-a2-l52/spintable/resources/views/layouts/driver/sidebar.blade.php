<div class="sidebar-collapse">
    <ul class="nav metismenu" id="side-menu">
        <li class="nav-header">
            <div class="dropdown profile-element">
                     <span>
                        <img alt="image" class="img-circle" src="{{ asset('assets/img/profile_small.jpg') }}" />
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
                                <b class="caret"></b>
                            </span> 
                        </span> 
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="{{ url('/logout') }}">Logout</a></li>
                    </ul>
            </div>
            <div class="logo-element">
                V
            </div>
        </li>
        <li class="active">
            <a href="index.html"><i class="fa fa-th-large"></i> <span class="nav-label">Main view</span></a>
        </li>
        <li>
            <a href="{{ url('driver/profile') }}"><i class="fa fa-diamond"></i> <span class="nav-label">Profile</span></a>
        </li>

    </ul>
</div>