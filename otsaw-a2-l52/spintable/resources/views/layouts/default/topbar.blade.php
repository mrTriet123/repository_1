<div class="row border-bottom">
          <nav class="navbar navbar-static-top grey-bg" role="navigation" style="margin-bottom: 0">

              @include('layouts.default.search')

              <ul class="nav navbar-top-links navbar-right">
                  <!-- Intro -->
                  <li>
                      <span class="m-r-sm text-muted welcome-message">Welcome to QUABII.</span>
                  </li>

                  <!-- Notification -->
                  <li class="dropdown">
                      <a class="dropdown-toggle count-info" data-toggle="dropdown" href="#">
                          <i class="fa fa-bell"></i>  <span class="label label-primary">8</span>
                      </a>
                      <ul class="dropdown-menu dropdown-alerts">
                          <li>
                              <a href="mailbox.html">
                                  <div>
                                      <i class="fa fa-envelope fa-fw"></i> You have 16 messages
                                      <span class="pull-right text-muted small">4 minutes ago</span>
                                  </div>
                              </a>
                          </li>
                          <li class="divider"></li>
                          <li>
                              <a href="profile.html">
                                  <div>
                                      <i class="fa fa-twitter fa-fw"></i> 3 New Followers
                                      <span class="pull-right text-muted small">12 minutes ago</span>
                                  </div>
                              </a>
                          </li>
                          <li class="divider"></li>
                          <li>
                              <a href="grid_options.html">
                                  <div>
                                      <i class="fa fa-upload fa-fw"></i> Server Rebooted
                                      <span class="pull-right text-muted small">4 minutes ago</span>
                                  </div>
                              </a>
                          </li>
                          <li class="divider"></li>
                          <li>
                              <div class="text-center link-block">
                                  <a href="notifications.html">
                                      <strong>See All Alerts</strong>
                                      <i class="fa fa-angle-right"></i>
                                  </a>
                              </div>
                          </li>
                      </ul>
                  </li>

                  <!-- Logout -->
                  <li>
                      <a href="{{ url('/logout') }}">
                          <i class="fa fa-sign-out"></i> Log out
                      </a>
                  </li>
              </ul>

          </nav>
</div>
