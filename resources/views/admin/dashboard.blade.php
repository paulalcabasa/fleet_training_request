@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Administrator's Dashboard</h1>
	</section>
	
	<section class="content container-fluid">
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua shadow">
					<div class="inner">
						<h3>@{{ dashboard.all_requests }}</h3>
	
						<p>All Requests</p>
					</div>
					<div class="icon">
						<i class="ion ion-android-list"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
	
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-green shadow">
					<div class="inner">
						{{-- <h3>53<sup style="font-size: 20px">%</sup></h3> --}}
						<h3>@{{ dashboard.pending_requests }}</h3>
	
						<p>Pending Requests</p>
					</div>
					<div class="icon">
						<i class="ion ion-android-notifications"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-yellow shadow">
					<div class="inner">
						<h3>@{{ dashboard.approved_requests }}</h3>
	
						<p>Approved Requests</p>
					</div>
					<div class="icon">
						<i class="ion ion-thumbsup"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
			<!-- ./col -->
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-red shadow">
					<div class="inner">
						<h3>@{{ dashboard.denied_requests }}</h3>
	
						<p>Denied Requests</p>
					</div>
					<div class="icon">
						<i class="ion ion-thumbsdown"></i>
					</div>
					<a href="#" class="small-box-footer">More info <i class="fa fa-arrow-circle-right"></i></a>
				</div>
			</div>
		</div>
	
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border">
				<h3 class="box-title">Customer's Requests</h3>
			</div>
			<div class="box-body">
				<table id="training_requests" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center text-uppercase">&nbsp;</th>
							<th class="text-center text-uppercase">Company Name</th>
							<th class="text-center text-uppercase">Contact Person</th>
							<th class="text-center text-uppercase">Email</th>
							<th class="text-center text-uppercase">Contact Number</th>
							<th class="text-center text-uppercase">Requesting For</th>
							<th class="text-center text-uppercase">Training Date</th>
							<th class="text-center text-uppercase">Status</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in items" v-bind:key="item.training_request_id">
							<td class="text-center">
								<!-- Split button -->
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-primary dropdown-toggle py-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-h"></i>
									</button>
									<ul class="dropdown-menu shadow-lg">
										<li class="text-left"><a href="#" v-on:click="openRequest(item.training_request_id)">
											<i class="fa fa-folder-open text-yellow"></i>
											Open Request
										</a></li>
										<li class="text-left"><a href="#" v-on:click="getApproverStatuses(item.training_request_id)">
											<i class="fa fa-th-list text-primary"></i>
											Approver Statuses
										</a></li>

										<li role="separator" class="divider"></li>
										<li class="dropdown-header">Your actions</li>
										<li v-if="item.request_status == 'approved'" class="text-center">
											<div class="label label-success">
												<i class="fa fa-check-circle"></i>
												already approved
											</div>
										</li>
										<li v-if="item.request_status != 'approved'" class="text-left"><a href="#" v-on:click="willApprove(item.training_request_id)">
											<i class="fa fa-check text-success"></i>&nbsp;
											Approve
										</a></li>
										<li v-if="item.request_status != 'approved'" class="text-left"><a href="#" v-on:click="willDeny(item.training_request_id)">
											<i class="fa fa-times text-danger"></i>
											Deny</a>
										</li>
									</ul>
								</div>
							</td>
							<td class="text-center">@{{ item.company_name }}</td>
							<td class="text-center">@{{ item.contact_person }}</td>
							<td class="text-center">@{{ item.email }}</td>
							<td class="text-center">@{{ item.contact_person }}</td>
							<td class="text-center">@{{ item.training_program.program_title }}</td>
							<td class="text-center">@{{ item.training_date | dateTimeFormat }}</td>
							<td class="text-center">
								<div :class="`label label-${item.request_status == 'pending' ? 'warning' : 'primary'}`">
									@{{ item.request_status }}
								</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
@include('admin.modals.request_details_modal')
@include('admin.modals.approver_statuses')
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
					dashboard: {},
					data_loaded: 0,
					items: [],
					training_request: {},
					approval_statuses: []
				}
			},
			created() {
				this.getDashboard();
				this.displayItems();
			},
			methods: {
				// Dashboard
				getDashboard() {
					axios.get(`${this.base_url}/admin/dashboard_statuses`)
					.then(({data}) => {
						this.dashboard = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getApproverStatuses(training_request_id) {
					axios.get(`${this.base_url}/admin/approver_statuses/${training_request_id}`)
					.then(({data}) => {
						this.approval_statuses = data;
						$('#approver_statuses').modal('show');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				// 
				displayItems() {
					return this.getItems()
					.then((data) => {
						$('#training_requests').DataTable({
							"paging": true,
							"info": true,
							"autoWidth": false
						});
					});
				},
				getItems() {
					return axios.get(`${this.base_url}/admin/training_requests/get`)
					.then(({data}) => {
						this.items = data;
						this.getDashboard();
					})
					.catch((error) => {
						console.log(error.response)
					});
				},
				openRequest(training_request_id) {
					axios.get(`${this.base_url}/admin/training_requests/get/${training_request_id}`)
					.then(({data}) => {
						this.training_request = data;
						this.data_loaded = 1;
						$('#request_details_modal').modal('show');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				willApprove(training_request_id) {
					swal({
						title: "Accept Request?",
						text: "Once approved, it will automatically send email to next approver",
						icon: "warning",
						buttons: {
							cancel: true,
							confirm: 'Approve'
						},
					})
					.then((res) => {
						if (res) {
							axios.put(`${this.base_url}/admin/update_request/${training_request_id}`, {request_status: 'approved'})
							.then(({data}) => {
								this.displayItems();
								swal('Success', 'Request has been approved', 'success');
							})
							.catch((error) => {
								console.log(error.response);
							});
						}
					});
				},
				willDeny(training_request_id) {
					swal({
						title: "Deny Request?",
						text: "you need to specify reason for an email response",
						icon: "error",
						dangerMode: true,
						buttons: {
							cancel: true,
							confirm: 'Yes please'
						},
					})
					.then((data) => {
						if (data) {
							swal('Success', 'Request has been denied', 'info');
						}
					});
				},
			}
		})
		document.querySelector('#dashboard_tab').setAttribute('class', 'active');
	</script>
@endpush