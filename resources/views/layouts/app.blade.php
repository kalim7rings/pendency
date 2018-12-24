<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title or '' }}</title>

    <link style="text/css" rel="stylesheet" href="{{ url('/css/app.css') }}">
    @yield('after_css')
    @routes
</head>
<body>
<div id="app">
@include('layouts.header')
@yield('content')
@include('layouts.footer')
</div>
@yield('after_script')
<script src="{{ url('/js/app.js') }}" type="text/javascript"></script>
</body>
</html>