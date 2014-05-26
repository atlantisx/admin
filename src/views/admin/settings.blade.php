@extends('themes/default::layouts.fluid')

@section('container')
    <div id="settings_page" class="container">
        <div class="row padded"></div>

        <div class="row">
            <div class="col-md-12">
                <div id="content" data-bind="template: 'settingsTemplate'" class="box"></div>
            </div>
        </div>
    </div>
@show

@section('javascript')
    <script type="text/javascript">
        var site_url = "{{ URL::to('/') }}",
            base_url = "{{ $baseUrl }}/",
            asset_url = "{{ $assetUrl }}",
            save_url = "{{ URL::route('admin_settings_save', array($config->getOption('name'))) }}",
            custom_action_url = "{{ URL::route('admin_settings_custom_action', array($config->getOption('name'))) }}",
            file_url = "{{ URL::route('admin_settings_display_file', array($config->getOption('name'))) }}",
            route = "{{ $route }}",
            csrf = "{{ Session::token() }}",
            language = "{{ Config::get('app.locale') }}",
            adminData = {
                name: "{{ $config->getOption('name') }}",
                title: "{{ $config->getOption('title') }}",
                data: <?php echo json_encode($config->getDataModel()) ?>,
                actions: <?php echo json_encode($actions) ?>,
                edit_fields: <?php echo json_encode($arrayFields) ?>,
                languages: <?php echo json_encode(trans('admin::knockout')) ?>
            };
    </script>
    {{ Form::token() }}
    <script id="settingsTemplate" type="text/html">
        {{ View::make("admin::clients.settings") }}
    </script>
    @parent
    @javascripts('admin')
@stop

