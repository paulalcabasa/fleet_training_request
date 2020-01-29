<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta name="author" content="Prince Ivan Kent Tiburcio">
    <link rel="shortcut icon" type="image/x-icon" href="{{{ url('public/favicon.ico') }}}">

	<!-- CSRF Token -->
	<meta name="csrf-token" content="{{ csrf_token() }}">

	<title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="{{ url('public/libraries/adminlte/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/libraries/adminlte/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/libraries/adminlte/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/libraries/adminlte/AdminLTE.min.css') }}">
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/skin-blue.min.css') }}">
	<link rel="stylesheet" href="{{ url('public/libraries/css/toastr.css') }}">
    
	<!-- Styles -->
	<link rel="stylesheet" href="{{ url('public/css/styles.css') }}">
	<link rel="stylesheet" href="{{ url('public/css/bootstrap_prototype.css') }}">
    
	<!-- Google Font -->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
	
	<style>
		.swal-button--confirm {
			background-color: #F44336;
		}
	</style>

	@stack('styles')
</head>
<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">@include('admin_templates.header')</header>

        <aside class="main-sidebar">@include('admin_templates.sidebar')</aside>

        <!-- Main Content -->
        <div class="content-wrapper" id="app">
            @yield('content')
        </div>

        <footer class="main-footer">@include('admin_templates.footer')</footer>

        <aside class="control-sidebar control-sidebar-dark">@include('admin_templates.control_sidebar')</aside>

        <div class="control-sidebar-bg"></div>
    </div>

    <!-- Scripts -->
	<script src="{{ url('public/js/manifest.js') }}"></script>
	<script src="{{ url('public/js/vendor.js') }}"></script>
	<script src="{{ url('public/js/admin.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/jquery.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/bootstrap.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/adminlte.min.js') }}"></script>
	<script src="{{ url('public/libraries/js/moment.js') }}"></script>
	<script src="{{ url('public/libraries/js/toastr.min.js') }}"></script>
	<script src="{{ url('public/js/toastr.config.js') }}"></script>
	<script src="{{ url('public/libraries/summernote-master/dist/summernote.min.js') }}"></script>
	<script>
        Vue.mixin({
            data() {
                return {
                    base_url: "{{ url('/') }}"
                }
            },
            filters: {
				dateFormat: function (value) {
					if (!value) return ''
					value = value.toString()
					return moment(value).format('MMMM D, YYYY')
				},
				dateTimeFormat: function (value) {
					if (!value) return ''
					value = value.toString()
					return moment(value).format('MMMM D, YYYY | h:mm:ss a')
				}
			},
        });
	</script>

	@stack('scripts')
</body>
</html>
