@if( isset($sidebar) )
<div class="sidebar-background">
    <div class="primary-sidebar-background"></div>
</div>

<div class="primary-sidebar">
    <ul class="nav navbar-collapse collapse navbar-collapse-primary">
         <li class="active">
            <span class="glow"></span>
            <a href="{{ url('/') }}/">
                <i class="icon-dashboard icon-2x"></i>
                <span>Home</span>
            </a>
        </li>

        <li class="dark-nav ">
            <span class="glow"></span>
            <a href="#application" data-toggle="collapse" class="accordion-toggle collapsed ">
                <i class="icon-beaker icon-2x"></i>
                <span>Aplikasi<i class="icon-caret-down"></i></span>
            </a>
            <ul class="collapse " id="application">
                <li class="">
                    <a href="{{ url('application/pendahuluan') }}"><i class="icon-hand-up"></i> Pendahuluan</a>
                </li>
            </ul>
        </li>
    </ul>
</div>
@endif