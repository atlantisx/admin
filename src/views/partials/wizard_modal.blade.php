@extends('admin::layouts.user')

@section('base')
    <div class="wizard" id="{{ $wizard['id'] }}">
        <h1>{{ $wizard['title'] }}</h1>
        @yield('cards')
    </div>
@stop

@section('javascript')
    @parent
    <script>
        elementRefresh();

        var wizard = $('#{{ $wizard['id'] }}').wizard({increaseHeight : 150});
        wizard.el.find('.modal-dialog').attr('style','width:800px');
        wizard.el.find('form').addClass('form-horizontal fill-up validatable');
        wizard.el.find('input,select').attr('data-validate','wizardValidate');
        wizard.show();

        function wizardValidate(el){
            var result = {
                status: !el.validationEngine('validate'),
                msg: null
            }
            return result;
        }
    </script>
@stop