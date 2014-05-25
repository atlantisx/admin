@extends('themes/default::layouts.fluid')

@section('container')
    <div class="container">
        <div class="row padded">
            @include('core::partials.error')
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