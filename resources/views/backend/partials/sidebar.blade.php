  <!-- Main Sidebar Container -->
  <div style="display: none">
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
          <!-- Brand Logo -->
          <a href="{{ route('home') }}" class="brand-link">
              <img src="{{ asset('admin') }}/img/AdminLTELogo.png" alt="AdminLTE Logo"
                  class="brand-image img-circle elevation-3" style="opacity: .8">
              <span class="brand-text font-weight-light">Bundler</span>
          </a>

          <!-- Sidebar -->
          <div class="sidebar">
              <!-- Sidebar user panel (optional) -->
              <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                  <div class="image">
                      <img src="{{ asset('admin') }}/img/user2-160x160.jpg" class="img-circle elevation-2"
                          alt="User Image">
                  </div>
                  <div class="info">
                      <a href="{{ url('/profile/' . Auth::user()->name) }}" class="d-block">{{ Auth::user()->name }}</a>
                  </div>
              </div>

              <!-- SidebarSearch Form -->
              {{-- <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search"
                    aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div> --}}

              <!-- Sidebar Menu -->
              <nav class="mt-2">
                  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu"
                      data-accordion="false">
                      <!-- Add icons to the links using the .nav-icon class
             with font-awesome or any other icon font library -->

                      @role('admin')
                          <li class="nav-item">
                              <a href="{{ route('public.home') }}"
                                  class="nav-link {{ Request::is('home') ? 'active' : null }}">
                                  <i class="nav-icon fas fa-tachometer-alt"></i>
                                  <p>
                                      Dashboard
                                  </p>
                              </a>
                          </li>
                      @endrole
                      @role('user')
                          {{-- <li
                          class="nav-item {{ Request::is('bundle*') ? 'menu-open' : null }} {{ Request::is('home') ? 'menu-open' : null }}">
                          <a class="nav-link {{ Request::is('bundle*') ? 'active' : null }}">
                              <i class="nav-icon fas fa-th"></i>
                              <p>
                                  BUNDLE
                                  <i class="right fas fa-angle-left"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a href="{{ route('bundle.index') }}"
                                      class="nav-link {{ Request::is('bundle') ? 'active' : null }}">
                                      <i class="nav-icon fas fa-list"></i>
                                      <p>
                                          BUNDLE List
                                      </p>
                                  </a>
                              </li>
                          </ul>
                      </li> --}}
                          <li
                              class="nav-item {{ Request::is('bundle*') ? 'menu-open' : null }} {{ Request::is('home') ? 'menu-open' : null }}">
                              <a class="nav-link  {{ Request::is('bundle*') ? 'active' : null }}"
                                  href="{{ route('bundle.index') }}">
                                  <i class="nav-icon fas fa-list"></i>
                                  <p>BUNDLE</p>
                              </a>
                          </li>
                          @php
                              $enrolled_package = auth()
                                  ->user()
                                  ->load('enrolledPackage')->enrolledPackage;
                          @endphp
                          @if ($enrolled_package->package_id == 3)
                              <li class="nav-item {{ Request::is('setting*') ? 'menu-open' : null }}">
                                  <a href="{{ route('setting.index') }}" class="nav-link">
                                      <i class="nav-icon fas fa-cogs"></i>
                                      <p>
                                          SETTINGS
                                      </p>
                                  </a>
                              </li>
                              <li class="nav-item {{ Request::is('setting*') ? 'menu-open' : null }}">
                                  <a href="{{ route('settings.payement.index') }}" class="nav-link">
                                      <i class="nav-icon fas fa-cogs"></i>
                                      <p>
                                          PAYMENT SETTINGS
                                      </p>
                                  </a>
                              </li>
                          @endif
                      @endrole
                      @role('admin')
                          <li class="nav-item">
                              <a class="nav-link {{ Request::is('roles') || Request::is('permissions') ? 'active' : null }}"
                                  href="{{ route('laravelroles::roles.index') }}">
                                  <i class="nav-icon fas fa-tasks"></i>
                                  <p>{!! trans('titles.laravelroles') !!}</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}"
                                  href="{{ url('/users') }}">
                                  <i class="nav-icon fas fa-users"></i>
                                  <p>{!! trans('titles.adminUserList') !!}</p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link {{ Request::is('users/create') ? 'active' : null }}"
                                  href="{{ url('/users/create') }}">
                                  <i class="nav-icon fas fa-plus"></i>
                                  <p> {!! trans('titles.adminNewUser') !!} </p>
                              </a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link {{ Request::is('package*') ? 'menu-open' : null }}"
                                  href="{{ route('package.index') }}">
                                  <i class="nav-icon fas fa-plus"></i>
                                  <p> {!! trans('titles.adminPackage') !!} </p>
                              </a>
                          </li>
                          <li class="nav-item {{ Request::is('setting*') ? 'menu-open' : null }}">
                              <a href="{{ route('setting.index') }}" class="nav-link">
                                  <i class="nav-icon fas fa-cogs"></i>
                                  <p>
                                      SETTINGS
                                  </p>
                              </a>
                          </li>
                      @endrole
                      <li class="nav-item {{ Request::is('profile*') ? 'menu-open' : null }}">
                          <a class="nav-link {{ Request::is('profile*') ? 'active' : null }}">
                              <i class="nav-icon fas fa-user"></i>
                              <p>
                                  Profile
                                  <i class="right fas fa-angle-left"></i>
                              </p>
                          </a>
                          <ul class="nav nav-treeview">
                              <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/' . Auth::user()->name) ? 'active' : null }}"
                                      href="{{ url('/profile/' . Auth::user()->name) }}">
                                      <i class="nav-icon fas fa-user-circle"></i>
                                      <p>
                                          View Profile
                                      </p>
                                  </a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link {{ Request::is('profile/' . Auth::user()->name . '/edit') ? 'active' : null }}"
                                      href="{{ url('/profile/' . Auth::user()->name) . '/edit' }}">
                                      <i class="nav-icon fas fa-edit"></i>
                                      <p>
                                          Edit Profile
                                      </p>
                                  </a>
                              </li>

                          </ul>
                      </li>
                      <li class="nav-item">
                          <a href="{{ route('logout') }}" class="nav-link"
                              onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                              <i class="nav-icon fas fa-sign-out-alt"></i>
                              <p>
                                  Logout
                              </p>
                          </a>
                          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                              @csrf
                          </form>
                      </li>
                  </ul>
              </nav>
              <!-- /.sidebar-menu -->
          </div>
          <!-- /.sidebar -->
      </aside>
  </div>
  <!-- Main Sidebar Container -->
