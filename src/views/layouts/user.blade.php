@section('stylesheet')
    @parent
    @stylesheets('user')
@stop

@section('navbar')
    @include('admin::partials.navbar')
    @include('admin::partials.sidebar')
@stop

@section('base')
<div id="wrap">
    <div class="main-content">
        @section('container')
        @show
    </div>
</div>
@show

@section('javascript')
    @parent
    @javascripts('user')
    <script>
        $(document).ready(function(){
            elementRefresh();
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
                dateFormat: 'd MM, y'
            });

            $("form.validatable").validationEngine({
                promptPosition: "topLeft"
            });

            $("select.select2").select2();

            $("select.uniform, input:file, .dataTables_length select").uniform();
        }
    </script>
@stop