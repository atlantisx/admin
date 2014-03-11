@if( isset($sidebar) )
<div class="sidebar-background">
    <div class="primary-sidebar-background"></div>
</div>

<div class="primary-sidebar">
    <ul class="nav navbar-collapse collapse navbar-collapse-primary">
         <li class="active">
            <span class="glow"></span>
            <a href="{{ $user_role->home_path }}">
                <i class="icon-dashboard icon-2x"></i>
                <span>{{ trans('admin::user.menu_label_home') }}</span>
            </a>
        </li>

        @if( isset($sidebar['applications']) )
            @foreach ($sidebar['applications'] as $key => $item)
                @include('admin::partials.sidebar_item')
            @endforeach
        @endif
    </ul>
</div>
@endif