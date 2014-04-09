@section('stylesheet')
    @parent
    @stylesheets('public')
@stop

@section('navbar')
    @include('layouts.partials.navbar-mobile')
@stop

@section('content')
<div class="container">
    <div class="col-md-4 col-md-offset-4">
        <div class="row login">
            <div class="col-md-12 text-center">
                <a href="{{ url('/') }}/"><img src="{{ asset('assets/img/logo.png') }}"></a>
            </div>
        </div>

        <div class="login box">
        @section('box')

            <div class="box-header">
                @section('box-header')
                <div class="title"></div>
                @show
            </div>

            <div class="box-content padded">
                @if( isset($_status) )
                <div class="alert alert-{{ $_status['type'] }}">
                    {{ $_status['message'] }}
                </div>
                @endif

                @section('box-content')
                @show
            </div>

        @show
        </div>
    </div>
</div>
@stop

@section('javascript')
    @parent
    @javascripts('public')
@stop