<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Prince Ivan Kent Tiburcio">
    <link rel="shortcut icon" type="image/x-icon" href="{{{ url('public/favicon.ico') }}}">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>{{ config('app.name', 'Laravel') }}</title>
	<link href='https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons' rel="stylesheet">
	{{-- <link rel="stylesheet" href="{{ url('public/libraries/css/bootstrap-datetimepicker.min.css') }}"> --}}
	{{-- <link rel="stylesheet" href="{{ url('public/css/styles.css') }}"> --}}
</head>
<body>
	<div id="app"></div>
	<script src="{{ url('public/js/app.js') }}"></script>
	{{-- <script src="{{ url('public/libraries/js/moment.js') }}"></script> --}}
	{{-- <script src="{{ url('public/libraries/js/bootstrap-datetimepicker.min.js') }}"></script> --}}
</body>
</html>