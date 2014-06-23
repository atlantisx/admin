<nav class="navbar navbar-default navbar-static-top" role="navigation">

    @include('admin::partials.mobilebar')

    <div class="navbar-collapse navbar-collapse-top collapse">
        <div class="navbar-right">
            <ul class="nav navbar-nav navbar-left">

                <!--==========================================
                 Superuser menu
                 ==========================================-->
                @if( isset($menu_admin) )
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        {{ trans('admin::admin.system') }} <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($menu_admin as $key => $item)
                            @include('admin::partials.navbar_item')
                        @endforeach
                    </ul>
                </li>
                @endif
            </ul>

            <ul class="nav navbar-nav navbar-left">
                <!--==========================================
                 User menu
                 ==========================================-->
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        {{ $user->first_name }} <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a href="{{ url('user/profile') }}"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                        <li><a href="{{ url('user/logout') }}"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>