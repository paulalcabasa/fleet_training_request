@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Participants</h1>
	</section>

	<section class="content container-fluid">
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border clearfix">
				<h3 class="box-title"><i class="fa fa-th-list"></i>@{{ trainingRequest.company_name }} | @{{ trainingRequest.training_request_id }}</h3>
				<button
				    v-on:click="create"
				    class="btn btn-sm btn-flat btn-default pull-right">
					<i class="fa fa-plus-circle"></i>
					ADD NEW
				</button>
			</div>
			<div class="box-body">
				<table id="datatable" class="table table-bordered table-hover" v-if="items.length > 0">
					<thead>
						<tr>
							<th class="text-center text-uppercase">Name</th>
							<th class="text-center text-uppercase">Result</th>
							<th class="text-center text-uppercase">Remarks</th>
							<th class="text-center text-uppercase">Actions</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(row, index) in items">
							<td class="text-center ">@{{ row.first_name }} @{{ row.last_name }}</td>
							<td class="text-center text-primary text-bold">@{{ row.result }}</td>
							<td class="text-center text-primary text-bold">@{{ row.remarks }}</td>
                            <td>
								<a href="#" v-on:click.prevent="edit(row)">
									<i class="fa fa-pencil text-primary"></i>
								</a>
                                <a href="#" v-on:click.prevent="deleteItem(row.id, index)" style="margin-left:1rem;">
									<i class="fa fa-trash text-danger"></i>
								</a>
							</td>
						</tr>
					</tbody>
				</table>
                <h3 v-if="items.length == 0" class='text-center'>No participants yet.</h3>
			</div>
		</div>
	</section>

	<div class="modal fade" id="form_modal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">@{{ form_title }}</h4>
                </div>
                <div class="modal-body">
                    <form v-on:keyup.enter="storeItem">
                        <input type="hidden" v-model="form.training_request_id">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">First name</label>
                                <input type="text" class="form-control" id="name" v-model="form.first_name">
                                <span v-if="errors.first_name" class="text-danger">
                                    @{{ errors.first_name[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="middle_name">Middle name</label>
                                <input type="text" class="form-control" id="middle_name" v-model="form.middle_name">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Last name</label>
                                <input type="text" class="form-control" id="last_name" v-model="form.last_name">
                                <span v-if="errors.last_name" class="text-danger">
                                    @{{ errors.last_name[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="result">Result</label>
                                <select name="" id="result" class="form-control" v-model="form.result">
                                    <option value="passed">Passed</option>
                                    <option value="failed">Failed</option>
                                </select> 
                              
                            </div>
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="" class="form-control" v-model="form.remarks" id="remarks" cols="5" rows="5"></textarea>
                               
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-flat btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                    <button type="button" class="btn btn-sm btn-flat btn-primary" v-on:click="storeItem"><i class="fa fa-check"></i> Save changes</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
					items: [],
					isEdit: 0,
					form: {
                        id : 0,
                        training_request_id : '',
                        first_name : '',
                        middle_name : '',
                        last_name : '',
                        result : '',
                        remarks : ''
                    },
					form_title: '',
					errors: [],
					image: '',
					image2: '',
                    trainingRequest : {!! json_encode($trainingRequest) !!}
				}
			},
			created() {
				this.getItems();
			},
			methods: {
				
				dataTable() {
					setTimeout(() => {
						$('#datatable').DataTable();
					});
				},
				getItems() {
                    axios.get(`${this.base_url}/admin/participants/get/${this.trainingRequest.training_request_id}`)
					.then(({data}) => {
						this.items = data;
						
						if (this.items.length > 0) {
							return this.dataTable();
						}
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getItem(emission_standard_id) {
					axios.get(`${this.base_url}/admin/emission_standard/get/${emission_standard_id}`)
					.then(({data}) => {
						this.form = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				storeItem() {
			
					if (this.isEdit) return this.updateItem();
                    this.form.training_request_id = this.trainingRequest.training_request_id;
					axios.post(this.base_url + '/admin/participant/store', this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully added!');
						this.resetForm();
					})
					.catch((error) => {
                        this.errors = error.response.data;
						console.log(error.response);
					});
				},
				updateItem() {
					axios.put(`${this.base_url}/admin/participant/update/${this.form.id}`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully updated!');
                        this.resetForm();
                        $('#form_modal').modal('hide');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				// --------
				create() {
					this.form_title = 'New participant';
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
					$('#form_modal').modal('show');
				},
				edit(data) {
					this.form_title = 'Update participant';
					this.isEdit = 1;
                    this.form.first_name = data.first_name;
                    this.form.middle_name = data.middle_name;
                    this.form.last_name = data.last_name;
                    this.form.result = data.result;
                    this.form.remarks = data.remarks;
                    this.form.id = data.id;
                    this.form.training_request_id = data.training_request_id;
                
					this.errors = [];
					$('#form_modal').modal('show');
				},
				resetForm() {
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
				},
                deleteItem(id, index) {
					swal({
						title: "Delete this participant?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							axios.delete(`${this.base_url}/admin/participant/delete/${id}`)
							.then(({data}) => {
								this.items.splice(index, 1);
								toastr.success('Successfully deleted!');
							})
							.catch((error) => {
								console.log(error.response);
							});
						}
					});
				},
			}
        })
		document.querySelector('#emission_tab').setAttribute('class', 'active');
	</script>
@endpush