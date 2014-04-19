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
            $.uniform.defaults.fileButtonHtml = '+';
            $.uniform.defaults.selectAutoWidth = false;

            $('.icheck').iCheck({
                checkboxClass: 'icheckbox_flat-aero',
                radioClass: 'iradio_flat-aero'
            });

            $('.datepicker').datepicker({
                todayBtn: false,
                dateFormat: 'yy-mm-dd'//'d MM, y'
            });

            $("select.select2").select2();

            $("select.uniform, input:file, .dataTables_length select").uniform();
        }
    </script>
@stop