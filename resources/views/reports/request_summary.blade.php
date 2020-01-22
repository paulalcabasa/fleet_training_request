@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/libraries/adminlte/bootstrap-datepicker.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Report</h1>
	</section>
	
	<section class="content container-fluid">
        <div class="row">
        <div class="col-md-6">

            <form method="post" action="xls_report_summary">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="box box-primary shadow-lg">
                    <div class="box-header with-border clearfix">
                        <h3 class="box-title"><i class="fa fa-th-list"></i>Request Summary</h3>
                    </div>
                    <div class="box-body">
                        <div class="form-group">
                            <label for="">Request Date From</label>
                            <input type="text"  class="form-control datepicker" name="start_date"/>
                        </div>
                        <div class="form-group">
                            <label for="">Request Date To</label>
                            <input type="text"  class="form-control datepicker" name="end_date"/>
                        </div>
                    </div>
                    <div class="box-footer">
                        <button
                            class="btn btn-sm btn-flat btn-primary pull-right"
                            type="submit">
                            Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
        <div class="col-md-6"></div>
        </div>
	</section>

</div>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('public/libraries/adminlte/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('public/libraries/js/moment.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
				
				}
			},
			created() {

            },
            mounted() {
                $('.datepicker').datepicker({
                    autoclose : true,
                    orientation: "bottom left",
                    format: 'yyyy-mm-dd'
                });
            },
			methods: {
			
			}
        })
		document.querySelector('#request_summary_tab').setAttribute('class', 'active');
	</script>
@endpush