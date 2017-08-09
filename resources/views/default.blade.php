<!DOCTYPE HTML>
<!--
	Directive by HTML5 UP
	html5up.net | @ajlkn
        Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
<head>
    <title>Free Swag. Plain & Simple.</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/jquery-editable-select.css') }}" />
</head>

<script src="{{ asset('js/jquery.min.js') }}"></script>
<script src="{{ asset('js/jquery-editable-select.js') }}"></script>

@include('_messages')
@yield('content')

</html>