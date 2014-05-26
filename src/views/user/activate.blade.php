@extends('themes/default::layouts.fixed-box')

@section('box-header')
    <div class="title">{{ trans('admin::user.activate_title') }}</div>
@show

@section('box-content')
    {{ Former::open()->method('GET')->class('separate-sections') }}
        <div class="input-group addon-left">
            <span class="input-group-addon" href="#"><i class="fa fa-user"></i></span>
            {{ Form::text('code', '', array('placeholder'=> trans('admin::user.activate_code')) ) }}
        </div>
        <div>
            <btn id="submit" class="btn btn-blue btn-block">{{ trans('admin::user.activation_btn_activate') }} <i class="fa fa-signin"></i></btn>
        </div>
    {{ Former::close() }}
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