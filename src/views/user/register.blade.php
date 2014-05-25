@extends('themes/default::layouts.fixed-box')

@section('box-header')
    <div class="title">{{ trans('admin::user.register_title') }}</div>
@show

@section('box-content')
    {{ Form::open(array('class'=>'separate-sections')) }}

    <div class="input-group addon-left">
        <span class="input-group-addon" href="#"><i class="fa fa-envelope"></i></span>
        {{ Form::text('email',Input::old('email'),array('class'=>'validate[required,custom[email]]','placeholder'=>trans('admin::user.register_label_email'))) }}
    </div>

    <div class="input-group addon-left">
        <span class="input-group-addon" href="#"><i class="fa fa-user"></i></span>
        {{ Form::text('first_name',Input::old('first_name'),array('placeholder'=>trans('admin::user.register_label_first_name'))) }}
    </div>

    <div class="input-group addon-left">
        <span class="input-group-addon" href="#"><i class="fa fa-user"></i></span>
        {{ Form::text('last_name',Input::old('last_name'),array('placeholder'=>trans('admin::user.register_label_last_name'))) }}
    </div>

    <div class="input-group addon-left">
        <span class="input-group-addon" href="#"><i class="fa fa-key"></i></span>
        {{ Form::password('password',array( 'class'=>'validate[required]', 'id'=>'password', 'placeholder'=>trans('admin::user.register_label_password'))) }}
    </div>

    <div class="input-group addon-left">
        <span class="input-group-addon" href="#"><i class="fa fa-key"></i></span>
        <input id="password_confirm" placeholder="{{ trans('admin::user.register_label_password_confirm') }}" type="password" class="validate[equals[password]]">
    </div>

    <div>
        <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.register_btn_register') }} <i class="fa fa-signin"></i></btn>
    </div>

    {{ Form::close() }}
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