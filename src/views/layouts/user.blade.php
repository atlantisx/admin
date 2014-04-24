@section('stylesheet')
    @parent
    @stylesheets('user')
@stop

@section('navbar')
    @include('admin::partials.navbar')
@stop

@section('base')
    @if( isset($sidebar) )
    <div class="main-content">
        @section('container')
        @show
    </div>
    @else
    <div id="wrap">
        @section('container')
        @show
    </div>
    @endif
@show

@section('javascript')
    @parent
    @javascripts('user')
    <script>
        $(document).ready(function(){
            elementRefresh();

            angular.element(document).ready(function() {
                angular.bootstrap(document, ['asng']);
            });
        });

        function elementRefresh(){
            $('.datepicker').datepicker({
                todayBtn: false,
                dateFormat: 'yy-mm-dd'//'d MM, y'
            });
        }
    </script>
@stop