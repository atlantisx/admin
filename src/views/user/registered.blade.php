@extends('themes/default::layouts.fixed-box')

@section('box-header')
    <div class="title">{{ trans('admin::user.registered_title') }}</div>
@show

@section('box-content')
    <div class="alert alert-success">
        {{ trans('admin::user.registered_text_success', array('email'=>$email)) }}
    </div>
    <div class="well">
        {{ trans('admin::user.registered_label_activation_code') }} : {{ $activation_code }}
    </div>
    <a href="{{ url('user/login/staff') }}" class="btn btn-blue btn-block">{{ trans('admin::user.login_btn_login') }} <i class="fa fa-signin"></i></a>
@show