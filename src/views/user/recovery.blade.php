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
                <div class="title">{{ trans('admin::user.recovery_password_title') }}</div>
            </div>
            <div class="box-content padded">
            @if( isset($get['status']) )
                <div class="alert alert-{{ $get['status']['type'] }}">
                    {{ $get['status']['message'] }}
                </div>
            @endif

            @if( isset($get['user_id']) && isset($get['code']) )
                {{ Former::open(URL::to('user/recovery'))->class('separate-sections') }}
                    {{ Former::input('login')->type('hidden')->value($get['login']) }}
                    {{ Former::input('code')->type('hidden')->value($get['code']) }}
                    {{ Former::label(trans('admin::user.label_password')) }}
                    <div class="input-group addon-left">
                        <span class="input-group-addon" href="#"><i class="icon-key"></i></span>
                        {{ Former::text('password')->type('password')->class('validate[required]') }}
                    </div>
                    {{ Former::label(trans('admin::user.label_password_confirm')) }}
                    <div class="input-group addon-left">
                        <span class="input-group-addon" href="#"><i class="icon-key"></i></span>
                        {{ Former::text('password_confirm')->type('password')->class('validate[equals[password]]') }}
                    </div>
                    <div>
                        <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.recovery_password_btn_reset') }} <i class="icon-random"></i></btn>
                    </div>
                {{ Former::close() }}
            @else
                {{ Former::open()->class('separate-sections') }}
                    <div class="input-group addon-left">
                        <span class="input-group-addon" href="#"><i class="icon-user"></i></span>
                        {{ Former::text('login')->placeholder(trans('admin::user.login_label_login')) }}
                    </div>
                    <div>
                        <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.btn_send') }} <i class="icon-envelope"></i></btn>
                    </div>
                {{ Former::close() }}
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
            $('form').validationEngine();

            $('#submit').on('click',function(){
                $('form').submit();
            });
        });
    </script>
@stop