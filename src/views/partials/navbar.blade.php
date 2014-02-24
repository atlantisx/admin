<nav class="navbar navbar-default navbar-inverse navbar-static-top" role="navigation">
    <div class="navbar-header">
        <a class="navbar-brand" href="{{ url('/') }}/">{{ $title }}</a>
        @if( isset($sidebar) )
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
            <span class="sr-only">Toggle Side Navigation</span>
            <i class="icon-th-list"></i>
        </button>
        @endif
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
            <span class="sr-only">Toggle Top Navigation</span>
            <i class="icon-align-justify"></i>
        </button>
    </div>

    <div class="navbar-collapse navbar-collapse-top collapse">
        <div class="navbar-right">
            <ul class="nav navbar-nav navbar-left">

                <!--==========================================
                 Superuser menu
                 ==========================================-->
                @if( isset($menu_admin) )
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <?php echo trans('admin::admin.system') ?>
                        <b class="caret"></b>
                    </a>
                    <ul class="dropdown-menu">
                        @foreach ($menu_admin as $key => $item)
                            @include('admin::partials.menu_item')
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
                    <a data-toggle="dropdown" class="dropdown-toggle dropdown-avatar" href="#">
                        <span>
                            <!--<img src="{{ Gravatar::src($user->email) }}" class="menu-avatar">-->
                            <span>{{ $user->first_name }} <i class="icon-caret-down"></i></span>
                        </span>
                        <!--<span class="badge badge-dark-red">0</span>-->
                    </a>
                    <ul class="dropdown-menu">
                        <!--
                        <li class="with-image">
                            <div class="avatar"><img src="{{ Gravatar::src($user->email,100) }}"></div>
                        </li>
                        <li class="divider"></li>
                        -->

                        <li><a href="{{ url('user/profile') }}"><i class="icon-user"></i> <span>Profile</span></a></li>
                        <!--<li><a href="{{ url('message/list/' . $user->id) }}"><i class="icon-envelope"></i> <span>Messages</span> <span class="label label-dark-red pull-right">0</span></a></li>-->
                        <li><a href="{{ url('user/logout') }}"><i class="icon-off"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>

    </div>
</nav>