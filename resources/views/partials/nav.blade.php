  <nav class="header-navbar navbar navbar-with-menu navbar-dark navbar-shadow">
      <div class="navbar-wrapper">
        <div class="navbar-header">
          <ul class="nav navbar-nav">
            <li class="nav-item mobile-menu hidden-md-up float-xs-left"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5 font-large-1"></i></a></li>
            <li class="nav-item">
              <a href="{{ route('frontend.user.index') }}" class="navbar-brand nav-link">
                <img alt="branding logo" src="/assets/images/logo/robust-logo-light.png" data-expand="/assets/images/logo/robust-logo-light.png" data-collapse="/assets/images/logo/robust-logo-small.png" class="brand-logo">
              </a>
            </li>
            <li class="nav-item hidden-md-up float-xs-right"><a data-toggle="collapse" data-target="#navbar-mobile" class="nav-link open-navbar-container"><i class="icon-ellipsis pe-2x icon-icon-rotate-right-right"></i></a>
            </li>
          </ul>
        </div>
        <div class="navbar-container content container-fluid">
          <div id="navbar-mobile" class="collapse navbar-toggleable-sm">

            @if(isset($logged_in_user))
            <ul class="nav navbar-nav">
              <li class="nav-item hidden-sm-down"><a class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="icon-menu5"></i></a></li>
            </ul>
            @endif

            <ul class="nav navbar-nav float-xs-right">

              @if(isset($logged_in_user))
              <li class="dropdown dropdown-user nav-item">
                <a href="#" data-toggle="dropdown" class="dropdown-toggle nav-link dropdown-user-link">
                  <span class="avatar avatar-online">
                    <img src="{{ $logged_in_user->small_avatar_url() }}" alt="avatar">
                    <i></i>
                  </span>
                  <span class="user-name">{{ $logged_in_user->name }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right">
                  <a href="{{ route('frontend.user.account') }}" class="dropdown-item"><i class="icon-head"></i> My Profile</a>
                  <div class="dropdown-divider"></div>
                  @if(session()->has('temp_user_id'))
                    <a href="{{ route('frontend.auth.logout-as') }}" class="dropdown-item"><i class="icon-power3"></i> End 'Login As'</a>
                  @endif
                  @if(!session()->has('temp_user_id'))
                    <a href="{{ route('frontend.auth.logout') }}" class="dropdown-item"><i class="icon-power3"></i> Logout</a>
                  @endif
                </div>
              </li>
              @endif

            </ul>
          </div>
        </div>
      </div>
    </nav>