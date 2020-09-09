<div class="modal fade" id="request_details_modal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Request Details</h4>
			</div>
			<div class="modal-body">

				<div class="nav-tabs-custom">
					<ul class="nav nav-tabs">
						<li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="true">Customer</a></li>
						<li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="false">Training</a></li>
						<li v-if="training_request.dealer_details"><a href="#tab_3" data-toggle="tab">Dealership</a></li>
						<li><a href="#tab_4" data-toggle="tab">Program</a></li>
						<li><a href="#tab_5" data-toggle="tab">Trainor</a></li>
						<li><a href="#tab_6" data-toggle="tab">Approval</a></li>
					</ul>
					<div class="tab-content">
						<div class="tab-pane active" id="tab_1">
							<div class="row">
								<div class="form-group col-md-6">
									<label>Company Name</label>
									<input type="text" v-model="training_request.company_name" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>Office Address</label>
									<input type="text" v-model="training_request.office_address" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>Contact Person</label>
									<input type="text" v-model="training_request.contact_person" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>Position</label>
									<input type="text" v-model="training_request.position" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>Email Address</label>
									<input type="text" v-model="training_request.email" class="form-control" />
								</div>

								<div class="form-group col-md-6">
									<label>Contact No</label>
									<input type="text" v-model="training_request.contact_number" class="form-control" />
								</div>
								<div class="form-group col-md-6">
									<label>Selling Dealer</label>
									<ul class="list-group">
										<li class="list-group-item" v-for="item in training_request.customer_dealers">
											<i class="fa fa-check-circle"></i>&nbsp;
											@{{ item.dealer }} | @{{ item.branch }}
										</li>
									</ul>
								</div>
								<div class="form-group col-md-6">
									<label>Owned vehicles</label>
									<ul class="list-group">
										<li class="list-group-item" v-for="item in training_request.customer_models">
											<i class="fa fa-check-circle"></i>&nbsp;
											@{{ item.model }}
										</li>
									</ul>
								</div>
							</div>
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_2">
							<div class="row">
								<div class="form-group col-md-7">
									<label>Training Participants</label>
									<ul class="list-group">
										<li class="list-group-item" v-for="item in training_request.customer_participants">
											<i class="fa fa-check-circle"></i>&nbsp;
											@{{ item.participant }} (@{{ item.quantity }})
										</li>
									</ul>
								</div>
								<div class="form-group col-md-5">
									<label>Request Training Date</label>
									<input type="text" v-bind:value="training_request.training_date | dateTimeFormat" class="form-control" readonly>
								</div>
								<div class="form-group col-md-5">
									<label>Training Venue</label>
									<input type="text" v-model="training_request.training_venue" class="form-control" readonly>
								</div>
								<div class="form-group col-md-7">
									<label>Address of Training Venue</label>
									<input type="text" v-model="training_request.training_address" class="form-control" readonly>
								</div>
							</div>
						</div>
						<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_3" v-if="training_request.dealer_details">
						 	<div class="row" v-if="data_loaded">
								<div class="form-group col-md-6">
									<label>Dealership Name</label>
									<input type="text" v-model="training_request.dealer_details.dealership_name" class="form-control" readonly>
								</div>
								<div class="form-group col-md-6">
									<label>Requestor</label>
									<input type="text" v-model="training_request.dealer_details.requestor_name" class="form-control" readonly>
								</div>
								<div class="form-group col-md-6">
									<label>Position Title</label>
									<input type="text" v-model="training_request.dealer_details.position" class="form-control" readonly>
								</div>
								<div class="form-group col-md-6">
									<label>Email</label>
									<input type="text" v-model="training_request.dealer_details.email" class="form-control" readonly>
								</div>
								<div class="form-group col-md-6">
									<label>Contact No</label>
									<input type="text" v-model="training_request.dealer_details.contact" class="form-control" readonly>
								</div>  
							</div> 
						</div>
							<!-- /.tab-pane -->
						<div class="tab-pane" id="tab_4">
							<div class="row">
								<div class="form-group col-md-7">
									<label>Training Program</label>
									<ul class="list-group">
										<li class="list-group-item" v-for="item in training_request.training_request_programs">
											<i class="fa fa-check-circle"></i>&nbsp;
											@{{ item.training_program.program_title }}
										</li>
									</ul>	
								</div>
								<div class="col-md-5">
									<div class="form-group">
										<label>Isuzu Specific Model</label>
										<input v-if="data_loaded" type="text" v-model="training_request.unit_model.model_name" class="form-control" readonly>
									</div>
									<div class="form-group" v-if="training_request.emission_standard">
										<label>Emission Standard</label>
										<input v-if="data_loaded" type="text" v-model="training_request.emission_standard.name" class="form-control" readonly>
									</div>
									<div class="form-group" v-if="training_request.body_type">
										<label>Body Type</label>
										<input v-if="data_loaded" type="text" v-model="training_request.body_type.name" class="form-control" readonly>
									</div>
								</div>

								
							</div>
						</div>

						<div class="tab-pane" id="tab_5">
							<div class="row">
								<table id="designated_trainors" class="table table-bordered table-hover">
									<thead>
										<tr>
											<th class="text-center text-uppercase">#</th>
											<th class="text-center text-uppercase">Trainor</th>
											<th class="text-center text-uppercase" v-if="training_request.status == 'new'">&nbsp;</th>
										</tr>
									</thead>
									<tbody v-if="training_request.status == 'new'">
										<tr v-for="(item, index) in designated_trainors" v-bind:key="index">
											<td >@{{ index+1 }}</td>
											<td >@{{ item.first_name }}  @{{ item.last_name }}</td>
											<td class="text-center">
											
												<button 
												v-if="item.designated_trainors.length > 0"
												v-on:click="excludeTrainor(item.person_id)"
												class="btn btn-xs btn-success">
													<i class="fa fa-check"></i>
												</button>
												<button 
												v-else 
												v-on:click="includeTrainor(item.person_id)"
												class="btn btn-xs btn-danger">
													<i class="fa fa-times"></i>
												</button>
											
											</td>
										</tr>
									</tbody>
									<tbody v-else>
										<tr v-for="(item, index) in designated_trainors" v-if="item.designated_trainors.length > 0" v-bind:key="index">
											<td class="text-center">@{{ index+1 }}</td>
											<td class="text-center">@{{ item.first_name }}  @{{ item.last_name }}</td>
										</tr>

									</tbody>
								</table>
							</div>
						</div>

						<div class="tab-pane" id="tab_6">
							<table id="training_requests" class="table table-bordered table-hover">
								<thead>
									<tr>
										<th class="text-center text-uppercase">&nbsp;</th>
										<th class="text-center text-uppercase">Approver</th>
										<th class="text-center text-uppercase">Email</th>
										<th class="text-center text-uppercase">Status</th>
									</tr>
								</thead>
								<tbody>
									<tr v-for="(item, index) in approval_statuses" v-bind:key="item.approval_status_id">
										<td class="text-center">@{{ index+1 }}</td>
										<td class="text-center">@{{ item.approver.first_name  }} @{{ item.approver.last_name  }}</td>
										<td class="text-center">@{{ item.approver.email }}</td>
										<td class="text-center">
											<i v-if="item.status == 'pending'" class="fa fa-clock-o text-yellow" style="font-size: 16px;"></i>
											<i v-else-if="item.status == 'approved'" class="fa fa-check-circle text-green" style="font-size: 16px;"></i>
											<i v-else class="fa fa-times-circle text-red" style="font-size: 16px;"></i>
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>
					<!-- /.tab-content -->
				</div>

	
			</div>
			<div class="modal-footer" >
				<button 
					v-if="training_request.status == 'new'"
					type="button" 
					class="btn btn-sm btn-flat btn-danger" 
					v-on:click="willDeny(training_request.training_request_id)" 
				>
					<i class="fa fa-times"></i> Deny
				</button>
				<button 
					v-if="training_request.status == 'new'"
                	class="btn btn-sm btn-success" 
                	v-on:click="willApprove"
                >
                    <i class="fa fa-check-circle"></i>&nbsp;
                    Approve
                </button>
				<button 
                	class="btn btn-sm btn-primary" 
                	v-on:click="updateRequest"
                >
                    <i class="fa fa-save"></i>&nbsp;
                    Save Changes
                </button>
			</div>
		</div>
	</div>
</div>