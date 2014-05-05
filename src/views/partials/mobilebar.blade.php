<div class="navbar-header">
    <a class="navbar-brand" href="{{ url('/') }}/">{{ $title }}</a>
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-top">
        <span class="sr-only">Toggle Top Navigation</span>
        <i class="icon-align-justify"></i>
    </button>
    @if( isset($sidebar) )
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse-primary">
        <span class="sr-only">Toggle Side Navigation</span>
        <i class="icon-align-justify"></i>
    </button>
    @endif
</div>