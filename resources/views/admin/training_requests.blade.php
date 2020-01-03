@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/bootstrap-datetimepicker.min.css') }}">
	<style>
		@media screen and (max-width: 1400px) {
			.table-responsive {
				margin-bottom: 15px;
				overflow-x: auto;
				overflow-y: hidden;
				width: 100%;
			}
			.table-responsive > .table {
				margin-bottom: 0;
			}
			.table-responsive > .table > thead > tr > th, .table-responsive > .table > tbody > tr > th, .table-responsive > .table > tfoot > tr > th, .table-responsive > .table > thead > tr > td, .table-responsive > .table > tbody > tr > td, .table-responsive > .table > tfoot > tr > td {
				white-space: nowrap;
			}
		}
	</style>
@endpush

@section('content')
<div v-cloak>
	<section class="content container-fluid">
		<div class="row">
			<div class="col-lg-3 col-xs-6">
				<!-- small box -->
				<div class="small-box bg-aqua shadow">
					<div class="inner">
						<h3>@{{ training_requests.all_requests }}</h3>
	
						<p>Total Requests</p>
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
						<h3>@{{ training_requests.approved_requests }}</h3>
	
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
				<div class="small-box bg-yellow shadow">
					<div class="inner">
						<h3>@{{ training_requests.pending_requests }}</h3>
	
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
				<div class="small-box bg-red shadow">
					<div class="inner">
						<h3>@{{ training_requests.denied_requests }}</h3>
	
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
				<h3 class="box-title">Training Requests</h3>
			</div>
			<div class="box-body table-responsive">
				<table id="training_requests" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-nowrap text-center text-uppercase">&nbsp;</th>
							<th class="text-nowrap text-center text-uppercase">Ref No.</th>
							<th class="text-nowrap text-center text-uppercase">Company Name</th>
							<th class="text-nowrap text-center text-uppercase">Training Date</th>
							<th class="text-nowrap text-center text-uppercase">Shift</th>
							<th class="text-nowrap text-center text-uppercase">Status</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in requests" v-bind:key="item.training_request_id">
							 <td class="text-nowrap">
	
								<div class="btn-group">
									<button type="button" class="btn btn-xs btn-primary dropdown-toggle py-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-gear"></i>
									</button>
									<ul class="dropdown-menu shadow-lg">
										<li class="text-left">
											<a v-on:click="openRequest(item.training_request_id)">
												<i class="fa fa-folder-open text-yellow"></i>
												Open Request
											</a>
										</li>
										<li v-if="item.request_status == 'approved'" class="text-left">
											<a href="#" v-on:click="getApproverStatuses(item.training_request_id)">
												<i class="fa fa-th-list text-default"></i>
												Approver Statuses
											</a>
										</li>
										<li v-if="item.requestor_confirmation == 'reschedule'" class="text-left">
											<a v-on:click="editSchedule(item.training_request_id)">
												<i class="fa fa-pencil text-default"></i>
												Edit Schedule
											</a>
										</li>
										<!-- <li class="text-left">
											<a v-on:click="showDesignatedTrainors(item.training_request_id)">
												<i class="fa fa-users text-default"></i>
												Trainors
											</a>
										</li> -->

								<!-- 		<li v-if="item.request_status != 'denied'" role="separator" class="divider"></li>
										<li v-if="item.request_status != 'denied' && item.request_status != 'approved'" class="dropdown-header">Your actions</li>
 -->
									<!-- 	<li 
										v-if="item.request_status == 'approved'" 
										class="text-center">
											<div :class="`label label-${item.color_stats} py-2 px-2`" style="pading: 8px;">
												<i v-if="item.stats == 'approved'" class="fa fa-check-circle mr-3"></i>
												@{{ item.stats.toUpperCase() }}
											</div>
										</li> -->

										<!-- <li v-if="item.request_status != 'denied' && item.request_status != 'approved'" class="text-left"><a href="#" v-on:click="showDesignatedTrainors(item.training_request_id)">
											<i class="fa fa-check text-success"></i>&nbsp;
											Approve
										</a></li>
										<li v-if="item.request_status != 'denied' && item.request_status != 'approved'" class="text-left"><a href="#" v-on:click="willDeny(item.training_request_id)">
											<i class="fa fa-times text-danger"></i>
											Disapprove</a>
										</li> -->
									</ul>
								</div>
							</td> 
						
							<td class="text-nowrap">@{{ item.training_request_id }}</td>
							<td class="text-nowrap">@{{ item.company_name }}</td>
							<td class="text-nowrap">@{{ item.training_date | dateFormat }}</td>
							<td class="text-nowrap">@{{ item.training_time }}</td>
							<td class="text-nowrap">
								<div  :class="status_colors[item.status]">
									@{{ item.status }}
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
@include('admin.modals.reschedule_modal')
@include('admin.modals.approver_statuses')
@include('admin.modals.designated_trainor_modal')
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	{{-- <script src="{{ url('public/libraries/adminlte/jquery.datetimepicker.full.min.js') }}"></script> --}}
	<script src="{{ url('public/libraries/adminlte/bootstrap-datetimepicker.min.js') }}"></script>
	<script>

		var table;

		$(function() {
			// $.datetimepicker.setLocale('en');
			// $('#datetimepicker').datetimepicker();
			$('#datetimepicker1').datetimepicker();
			
			$('.table-responsive').on('show.bs.dropdown', function () {
				$('.table-responsive').css( "overflow", "inherit" );
			});

			$('.table-responsive').on('hide.bs.dropdown', function () {
				$('.table-responsive').css( "overflow", "auto" );
			})
		});

		new Vue({
			el: '#app',
			data() {
				return {
					training_requests: {},
					data_loaded: 0,
					items: [],
					training_request: {},
					approval_statuses: [],
					training_request_id: 0,
					designated_trainors: [],
					toggledButton: false,
					status_colors : {
						'pending' : 'label label-warning',
						'approved': 'label label-success',
						'confirmed': 'label label-success',
						'denied'  : 'label label-danger',
						'new'     : 'label label-info',
					}
				}
			},
			computed: {
				requests () {
					var stats = ''
					var color_stats = ''
					var items = this.items

					items.forEach((el, i) => {
						el.approval_statuses.forEach(status => {
							if (status.status === 'approved') {
								stats = 'approved'
								color_stats = 'success'
							}
							if (status.status === 'denied') {
								stats = 'denied'
								color_stats = 'danger'
							}
							else {
								stats = 'pending'
								color_stats = 'warning'
							}
						});

						el['stats'] = stats
						el['color_stats'] = color_stats
					});

					return items
				}	
			},
			created() {
				this.getDashboard();
				this.getItems();
			},
			methods: {
				saveSchedule() {
					var training_date = document.getElementById('training_date').value;
					axios.put(`${this.base_url}/admin/training_requests/reschedule/${this.training_request_id}`, {training_date: training_date})
					.then(({data}) => {
						if (data) {
							$('#reschedule_modal').modal('hide');
							this.getItems();
							swal('Success!', 'Training Program has been rescheduled.', 'success', {timer:4000,button:false});
						}
					})
					.catch((error) => {
						console.log(error.response);
						swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
					});
				},
				editSchedule(training_request_id) {
					axios.get(`${this.base_url}/admin/training_requests/get/${training_request_id}`)
					.then(({data}) => {
						this.training_request_id = data.training_request_id;
						$('#reschedule_modal').modal('show');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getDashboard() {
					axios.get(`${this.base_url}/admin/training_requests_statuses`)
					.then(({data}) => {
						this.training_requests = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getApproverStatuses(training_request_id) {
					axios.get(`${this.base_url}/admin/approver_statuses/${training_request_id}`)
					.then(({data}) => {
						this.approval_statuses = data;
						//$('#approver_statuses').modal('show');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getItems() {
					return axios.get(`${this.base_url}/admin/training_requests/get`)
						.then(({data}) => {
							this.items = data;

							if($.fn.dataTable.isDataTable('#training_requests')){
								table.destroy();
							}
                 
							this.$nextTick(() => {
								table = $('#training_requests').DataTable({
									ordering: false
								});
							});
						})
						.catch((error) => {
							console.log(error.response)
						});
				},
				openRequest(training_request_id) {
					var self = this;
					self.training_request_id = training_request_id;
					axios.get(`${this.base_url}/admin/training_requests/get/${training_request_id}`)
					.then(({data}) => {
						self.training_request = data;
						self.data_loaded = 1;
						$('#request_details_modal').modal('show');
					})
					.then( () => {
						self.getDesignatedTrainors(self.training_request_id);
					})
					.then( () => {
						self.getApproverStatuses(self.training_request_id);
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				willApprove() {
					var self = this;
					axios.get(`${self.base_url}/admin/designated_trainors/assigned_trainors/${self.training_request_id}`)
					.then(({data}) => {
						var canProceed = false;
						data.forEach(element => {
							if (element.designated_trainors.length > 0) {
								canProceed = true;
							}
						});

						if (!canProceed) {
							return swal('Sorry!', 'Please, Choose atleast (1) Trainor', 'error', {timer:4000,button:false});
						}
						else {
							swal({
								title: "Accept Request?",
								text: "Once approved, it will automatically send email to next approver",
								icon: "warning",
								closeOnClickOutside: false,
								buttons: {
									cancel: true,
									confirm: 'Approve'
								},
							})
							.then((res) => {
								if (res) {
									axios.put(`${self.base_url}/admin/update_request/${self.training_request_id}`, {request_status: 'pending'})
									.then(({data}) => {
										if (data) {
									
											self.getItems();
											self.getDashboard();
											
											swal({
												title: "Alright!",
												text: "Request has been approved",
												icon: "success",
												button: false,
												timer: 4000,
											})
										}
									})
									.then( () => {
										$('#request_details_modal').modal('hide');
									})
									.catch((error) => {
										console.log(error.response);
									});
								}
							});
						}
					})
					.catch((error) => {
						console.log(error.response);
						swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
					});
				},
				willDeny(training_request_id) {
					swal({
						title: "Disapprove Request?",
						text: "Note: you can not undo changes after it.",
						icon: "error",
						dangerMode: true,
						closeOnClickOutside: false,
						buttons: {
							cancel: true,
							confirm: 'Yes please'
						},
					})
					.then((data) => {
						if (data) {
							axios.put(`${this.base_url}/admin/update_request/${training_request_id}`, {request_status: 'denied'})
							.then(({data}) => {
								if (data) {
									this.getItems();
									this.getDashboard();
									swal('Success!', 'Request has been denied', 'success', {timer:4000,button:false});
								}
							})
							.then( () => {
								$('#request_details_modal').modal('hide');
							})
							.catch((error) => {
								console.log(error.response);
								swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
							});
						}
					});
				},
				getRequest: function(training_request_id) {
				
					axios.get(`${this.base_url}/admin/training_requests/get/${training_request_id}`)
					.then(({data}) => {
						this.training_request = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getDesignatedTrainors: function(training_request_id) {
					var self = this;
					axios.get(`${this.base_url}/admin/designated_trainors/assigned_trainors/${training_request_id}`)
					.then(({data}) => {
						self.training_request_id = training_request_id;
						self.designated_trainors = data;
					})
					.catch((error) => {
						console.log(error.response);
						swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
					});
				},
				includeTrainor: function(person_id) {
					var self = this;
					axios.post(
						`${this.base_url}/admin/designated_trainors/assign_trainor`, 
						{
							training_request_id: self.training_request_id,
							person_id: person_id
						}
					)
					.then(({data}) => {
						if (data) {
							axios.get(`${this.base_url}/admin/designated_trainors/assigned_trainors/${self.training_request_id}`)
							.then(({data}) => {
								self.designated_trainors = data;
							})
							.catch((error) => {
								console.log(error.response);
								swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
							});
						}
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				excludeTrainor: function(person_id) {
					var self = this;
					swal({
						title: "Remove Trainor?",
						text: "",
						icon: "warning",
						buttons: {
							cancel: true,
							confirm: 'Proceed'
						},
						closeOnClickOutside: false
					})
					.then((res) => {
						if (res) {
							axios.post(`${self.base_url}/admin/designated_trainors/remove_trainor`,
							{
								training_request_id: self.training_request_id,
								person_id: person_id
							})
							.then(({data}) => {
								if (data) {
									axios.get(`${self.base_url}/admin/designated_trainors/assigned_trainors/${self.training_request_id}`)
									.then(({data}) => {
										self.designated_trainors = data;
									})
									.catch((error) => {
										console.log(error.response);
										swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
									});
								}
							})
							.catch((error) => {
								console.log(error.response);
							});
						}
					});
				}
			}
		})
		document.querySelector('#training_requests_tab').setAttribute('class', 'active');
	</script>
@endpush