@extends('layouts.box')

@section('box-header')
    <div class="title">{{ trans('admin::user.login_title') }}</div>
@show

@section('box-content')
    {{ Former::open()->class('separate-sections') }}
        <div class="input-group addon-left">
            <span class="input-group-addon" href="#"><i class="icon-user"></i></span>
            {{ Former::text('email')->placeholder(trans('admin::user.login_label_login')) }}
        </div>

        <div class="input-group addon-left">
            <span class="input-group-addon" href="#"><i class="icon-key"></i></span>
            {{ Former::password('password')->placeholder(trans('admin::user.login_label_password')) }}
        </div>

        <div>
            <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.login_btn_login') }} <i class="icon-signin"></i></btn>
        </div>
    {{ Former::close() }}

    <div>
        <i class="icon icon-umbrella"></i> {{ trans('admin::user.login_text_help', array('link'=>url('public/page/help'))) }}
    </div>
@show

@section('javascript')
    @parent
    <script>
        $(document).ready(function(){
            $('#submit').on('click',function(){
                $('form').submit();
            });
        });
    </script>
@stop