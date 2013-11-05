<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>{{ $title }}</title>
    @section('stylesheet')
    <!-- StyleSheets
    ================================================== -->
    @stylesheets('common','application')
    @show
</head>
<body>
@include('layouts.partials.iesupport')

@section('navbar')
@show

@section('base')
<div id="wrap">
    @if (isset($errors) && count($errors->all()) > 0)
        @include('layouts.partials.error')
    @endif
    @yield('content')
</div>
@show

@include('layouts.partials.footer')

<!-- JavaScripts
================================================== -->
@section('javascript')
    @javascripts('common')
@show

</body>
</html>
