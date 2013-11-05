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
                <div class="title">{{ trans('admin::user.activation_title') }}</div>
            </div>
            <div class="box-content padded">
            @if( isset($data['status']) )
                <div class="alert alert-{{ $data['status']['type'] }}">
                    {{ $data['status']['message'] }}
                </div>
            @else
                {{ Form::open(array('class'=>'separate-sections')) }}
                <div class="input-group addon-left">
                    <span class="input-group-addon" href="#"><i class="icon-user"></i></span>
                    {{ Form::text('login','',array('placeholder'=>'email / username')) }}
                </div>

                <div>
                    <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.activation_btn_send') }} <i class="icon-signin"></i></btn>
                </div>
                {{ Form::close() }}
            @endif
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