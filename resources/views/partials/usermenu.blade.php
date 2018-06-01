  
     <!-- main menu-->
        <div class="main-menu menu-dark menu-shadow">
          
          <!-- main menu header-->
          {{-- <div class="main-menu-header">
          </div> --}}
          <!-- / main menu header-->

          <!-- main menu content-->
          <div class="main-menu-content">
            <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">


              {{-- USER ADMIN --}}
              @permissions(['manage-users', 'manage-roles', 'manage-all-events','settings'])
              <li class=" navigation-header"><span data-i18n="nav.category.admin">Admin</span><i data-toggle="tooltip" data-placement="right" data-original-title="Admin" class="icon-ellipsis icon-ellipsis"></i>
              </li>

                @permissions('settings')
                <li class=" nav-item"><a href="#"><i class="icon-settings"></i><span data-i18n="nav.settings.main" class="menu-title">Settings</span></a>
                  <ul class="menu-content">

                    <li class="{{ active_class(Active::checkUriPattern('admin.access.settings_event_type'), 'active') }}">
                      <a href="{{ route('admin.access.settings_event_type.index') }}" data-i18n="nav.settings_event_type" class="menu-item">Event Types</a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/access/settings_event_category'), 'active') }}">
                      <a href="{{ route('admin.access.settings_event_category.index') }}" data-i18n="nav.settings_event_category" class="menu-item">Event Categories</a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/access/settings_vendor_category'), 'active') }}">
                      <a href="{{ route('admin.access.settings_vendor_category.index') }}" data-i18n="nav.settings_vendor_category" class="menu-item">Vendor Categories</a>
                    </li>

                    <li class="{{ active_class(Active::checkUriPattern('admin/access/agreements'), 'active') }}">
                      <a href="{{ route('admin.access.agreements.index') }}" data-i18n="nav.settings.agreements" class="menu-item"> Agreements</a>
                    </li>

                  </ul>
                </li>
                @endauth

                @permissions(['manage-users', 'manage-roles'])
                <li class=" nav-item"><a href="#"><i class="icon-equalizer2"></i><span data-i18n="nav.users.main" class="menu-title">Global</span></a>
                  <ul class="menu-content">


                    @permission('manage-roles')
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/role*'), 'active') }}">
                      <a href="{{ route('admin.access.role.index') }}" data-i18n="nav.users.user_roles" class="menu-item">Roles</a>
                    </li>
                    @endauth

                    <li class="{{ active_class(Active::checkUriPattern('dashboard/templates'), 'active') }}">
                      <a href="{{ route('frontend.view.tpl') }}" data-i18n="nav.tpl.list" class="menu-item">Email Templates</a>
                    </li>


                  </ul>
                </li>
                @endauth


                <li class=" nav-item"><a href="#"><i class="icon-head"></i><span data-i18n="nav.users.main" class="menu-title">Users</span></a>
                  <ul class="menu-content">
                    @permissions(['manage-users', 'manage-roles'])
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/user*'), 'active') }}">
                      <a href="{{ route('admin.access.user.index') }}" data-i18n="nav.users.user_profiles" class="menu-item">Management</a>
                    </li>
                    @endauth
                    
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/event/org*'), 'active') }}">
                      <a href="{{ route('admin.access.org.index') }}" data-i18n="nav.users.user_profiles" class="menu-item">Organizations</a>
                    </li>

                  </ul>
                </li>
              

                {{-- Events admin --}}
                @permissions(['manage-all-events'])

                <li class=" nav-item "><a href="#"><i class="icon-profile"></i><span data-i18n="nav.users.main" class="menu-title">Events</span></a>
                  <ul class="menu-content">
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/event*'), 'open') }}">
                      <a href="{{ route('frontend.event.index') }}" data-i18n="nav.events.list" class="menu-item">Manage</a>
                    </li>
                    
                    

                  </ul>
                </li>
                @endauth
                
              @endauth

              {{-- EVENTS --}}
              @permissions(['view-events'])
              <li class=" navigation-header"><span data-i18n="nav.category.actions">Actions</span><i data-toggle="tooltip" data-placement="right" data-original-title="Actions" class="icon-ellipsis icon-ellipsis"></i>
              </li>

              <li class=" nav-item {{ active_class(Active::checkUriPattern('dashboard/event*'), 'open') }}"><a href="#"><i class="icon-calendar4"></i><span data-i18n="nav.events.main" class="menu-title">Events</span></a>
                <ul class="menu-content">
                  <li class="{{ active_class(Active::checkUriPattern('dashboard/event'), 'active') }}">
                    <a href="{{ route('frontend.event.index') }}" data-i18n="nav.events.list" class="menu-item">View</a>
                  </li>

                  @permissions(['manage-own-events', 'manage-all-events'])
                  <li class="{{ active_class(Active::checkUriPattern('dashboard/event/create*'), 'active') }}">
                    <a href="{{ route('frontend.event.create') }}" data-i18n="nav.events.create" class="menu-item">Create New</a>
                  </li>
                  @endauth

                </ul>
              </li>
              @endauth

              {{-- vendor --}}
              @if(access()->user()->hasRoles([config('event.vendor.role.id')]))
              <li class=" navigation-header"><span data-i18n="nav.vendor.main">Vendor</span><i data-toggle="tooltip" data-placement="right" data-original-title="Vendor" class="icon-ellipsis icon-ellipsis"></i>
              </li>

              <li class=" nav-item {{ active_class(Active::checkUriPattern('dashboard/vendor*'), 'open') }}"><a href="#"><i class="icon-calendar3"></i><span data-i18n="nav.vendor.main" class="menu-title">Events</span></a>
                <ul class="menu-content">
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/vendor_confirmed'), 'active') }}">
                      <a href="{{ route('admin.access.vendor_confirmed.index') }}" data-i18n="nav.vendor_confirmed" class="menu-item">Confirmed</a>
                    </li>
                    <li class="{{ active_class(Active::checkUriPattern('admin/access/vendor_pending'), 'active') }}">
                      <a href="{{ route('admin.access.vendor_pending.index') }}" data-i18n="nav.vendor_pending" class="menu-item">Pending</a>
                    </li>





                </ul>
              </li>
              @endif


            </ul>
          </div>
          <!-- /main menu content-->

           <!-- main menu footer-->
          <div class="main-menu-footer">
            <div class="header text-xs-center">
              
            </div>
          </div>
          <!-- main menu footer-->
          
        </div>
        <!-- / main menu-->