@section('stylesheet')
    @parent
    @stylesheets('public')
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
            <div class="box-header">
                <div class="title">{{ trans('admin::user.activate_title') }}</div>
            </div>
            <div class="box-content padded">
            @if( isset($_status) )
                <div class="alert alert-{{ $_status['type'] }}">
                    {{ $_status['message'] }}
                </div>
            @endif
            {{ Former::open()->method('GET')->class('separate-sections') }}
                <div class="input-group addon-left">
                    <span class="input-group-addon" href="#"><i class="icon-user"></i></span>
                    {{ Form::text('code', '', array('placeholder'=> trans('admin::user.activate_code')) ) }}
                </div>
                <div>
                    <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.activation_btn_activate') }} <i class="icon-signin"></i></btn>
                </div>
            {{ Former::close() }}
            </div>
        </div>

    </div>
</div>
@stop

@section('javascript')
    @parent
    @javascripts('public')
    <script>
        $(document).ready(function(){
            $('#submit').on('click',function(){
                $('form').submit();
            });
        });
    </script>
@stop