@extends('admin::layouts.user')

@section('container')
    <div class="container">
        <div class="row padded">
            @if( isset($_status) )
            <div class="alert alert-{{ $status['type'] }}">
                {{ $_status['message'] }}
            </div>
            @endif
        </div>

        <div class="row">
            <div class="col-md-4">
                @portlet('user.tag')
            </div>

            <div class="col-md-8">

            </div>
        </div>
    </div>
@stop

@section('javascript')
    @parent
@stop