<!-- Navbar -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
        <li>
            <button class="btn btn-sm btn-primary" onclick="history.back()" data-toggle="tooltip" data-placement="bottom"
                title="Back"><i class="fa fa-long-arrow-left"></i></button>
        </li>
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('home') }}" class="brand-link">
                <img src="{{ asset('admin') }}/img/AdminLTELogo.png" alt="AdminLTE Logo"
                    class="brand-image img-circle elevation-3" style="opacity: .8">
                <span class="text-dark">Bundler</span>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Navbar Search -->
        @guest
            <li><a class="nav-link" href="{{ route('login') }}">{{ trans('titles.login') }}</a></li>
            @if (Route::has('register'))
                <li><a class="nav-link" href="{{ route('register') }}">{{ trans('titles.register') }}</a></li>
            @endif
        @else
            <li class="nav-item dropdown">
                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false" v-pre>
                    @if (Auth::User()->profile && Auth::user()->profile->avatar_status == 1)
                        <img src="{{ Auth::user()->profile->avatar }}" alt="{{ Auth::user()->name }}"
                            class="user-avatar-nav">
                    @else
                        <div class="user-avatar-nav"></div>
                    @endif
                    {{ Auth::user()->name }} <span class="caret"></span>
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    @role('admin')
                        <a class="dropdown-item {{ Request::is('users', 'users/' . Auth::user()->id, 'users/' . Auth::user()->id . '/edit') ? 'active' : null }}"
                            href="{{ url('/users') }}">
                            {!! trans('titles.adminUserList') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('roles') || Request::is('permissions') ? 'active' : null }}"
                            href="{{ route('laravelroles::roles.index') }}">
                            {!! trans('titles.laravelroles') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('users/create') ? 'active' : null }}"
                            href="{{ url('/users/create') }}">
                            {!! trans('titles.adminNewUser') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('package*') ? 'active' : null }}"
                            href="{{ route('package.index') }}">
                            {!! trans('titles.adminPackage') !!}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item {{ Request::is('setting*') ? 'active' : null }}"
                            href="{{ route('setting.index') }}">
                            Settings
                        </a>
                        <a class="dropdown-item {{ Request::is('payment*') ? 'active' : null }}"
                            href="{{ route('settings.payement.index') }}">
                            Payment Settings
                        </a>
                        <a class="dropdown-item {{ Request::is('plan*') ? 'active' : null }}"
                            href="{{ route('settings.plan.index') }}">
                            Plan Settings
                        </a>
                    @endrole
                    @role('user')
                        <a class="dropdown-item {{ Request::is('bundle*') ? 'active' : null }}"
                            href="{{ route('bundle.index') }}">
                            Bundle List
                        </a>
                        @php
                            $enrolled_package = auth()
                                ->user()
                                ->load('enrolledPackage')->enrolledPackage;
                        @endphp
                        @if ($enrolled_package->package_id == 3)
                            <div class="dropdown-divider"></div>
                            <a href="{{ route('setting.index') }}"
                                class="dropdown-item {{ Request::is('setting*') ? 'active' : null }}">
                                Settings
                            </a>
                        @endif
                    @endrole
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item {{ Request::is('profile/' . Auth::user()->name, 'profile/' . Auth::user()->name . '/edit') ? 'active' : null }}"
                        href="{{ url('/profile/' . Auth::user()->name) }}">
                        {!! trans('titles.profile') !!}
                    </a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="{{ route('logout') }}"
                        onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        @endguest

    </ul>
</nav>
<!-- /.navbar -->
