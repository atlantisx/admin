@extends('admin::user.register')

@section('box')
    <div class="box-header">
        <div class="title">{{ trans('admin::user.registered_title') }}</div>
    </div>
    <div class="box-content padded">
        <div class="alert alert-success">
            {{ trans('admin::user.registered_text_success', array('email'=>$email)) }}
        </div>
        <div class="well">
            {{ trans('admin::user.registered_label_activation_code') }} : {{ $activation_code }}
        </div>
    </div>
@stop