@extends('admin::layouts.user')

@section('base')
<div id="wrap">
<div class="container">
    <div class="row padded">
        @if( isset($status) )
        <div class="alert alert-{{ $status['type'] }}">
            {{ $status['message'] }}
        </div>
        @endif
    </div>

    <div class="row">

    </div>
</div>
</div>
@stop

@section('javascript')
    @parent
@stop