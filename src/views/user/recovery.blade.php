@extends('themes/default::layouts.fixed-box')

@section('box-header')
    <div class="title">{{ trans('admin::user.recovery_password_title') }}</div>
@show

@section('box-content')
    @if( isset($user_id) && isset($code) )
        {{ Former::open(URL::to('user/recovery'))->class('separate-sections') }}
            {{ Former::input('login')->type('hidden')->value($login) }}
            {{ Former::input('code')->type('hidden')->value($code) }}
            {{ Former::label(trans('admin::user.label_password')) }}
            <div class="input-group addon-left">
                <span class="input-group-addon" href="#"><i class="fa fa-key"></i></span>
                {{ Former::text('password')->type('password')->class('validate[required]') }}
            </div>
            {{ Former::label(trans('admin::user.label_password_confirm')) }}
            <div class="input-group addon-left">
                <span class="input-group-addon" href="#"><i class="fa fa-key"></i></span>
                {{ Former::text('password_confirm')->type('password')->class('validate[equals[password]]') }}
            </div>
            <div>
                <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.recovery_password_btn_reset') }} <i class="fa fa-random"></i></btn>
            </div>
        {{ Former::close() }}
    @else
        {{ Former::open()->class('separate-sections') }}
            <div class="input-group addon-left">
                <span class="input-group-addon" href="#"><i class="fa fa-user"></i></span>
                {{ Former::text('login')->placeholder(trans('admin::user.login_label_login')) }}
            </div>
            <div>
                <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.btn_send') }} <i class="fa fa-envelope"></i></btn>
            </div>
        {{ Former::close() }}
    @endif
@show

@section('javascript')
    @parent
    <script>
        $(document).ready(function(){
            $('form').validationEngine({
                validateNonVisibleFields: true,
                autoPositionUpdate: true,
                promptPosition: "inline",
                showArrow: false,
                scroll: false
            });

            $('#submit').on('click',function(){
                $('form').submit();
            });
        });
    </script>
@stop