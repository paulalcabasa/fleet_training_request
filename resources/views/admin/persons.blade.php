@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>
			List of Persons
		</h1>
		<ol class="breadcrumb">
			<li>
				<button
				v-on:click="createItem"
				class="btn btn-sm btn-flat btn-default pull-right" style="margin-top: -8px;">
					<i class="fa fa-plus-circle"></i>
					ADD PERSON
				</button>
			</li>
		</ol>
	</section>

	<section class="content container-fluid">
		<div class="box box-default shadow-lg">
			<div class="box-body">
				<table id="approver_table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center text-uppercase">Type</th>
							<th class="text-center text-uppercase">Name</th>
							<th class="text-center text-uppercase">Email</th>
							<th class="text-center text-uppercase">Position</th>
							<th class="text-center text-uppercase">Actions</th>
			
						</tr>
					</thead>
					<tbody>
						<tr v-for="(row, index) in persons" v-bind:key="row.person_id">
						<!-- 	<td class="text-center">
								<div class="btn-group">
									<button type="button" class="btn btn-sm btn-primary dropdown-toggle py-0" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										<i class="fa fa-ellipsis-h"></i>
									</button>
									<ul class="dropdown-menu shadow-lg">
										<li class="text-left"><a v-on:click="editItem(row.person_id)">
											<i class="fa fa-pencil text-primary"></i>
											Update</a>
										</li>
										<li class="text-left"><a v-on:click="deleteItem(row.person_id)">
											<i class="fa fa-times text-danger"></i>
											Delete</a>
										</li>
									</ul>
								</div>
							</td> -->
							<td>@{{ row.person_type }}</td>
							<td >@{{ row.first_name + " " + row.last_name }}</td>
							<td >@{{ row.email }}</td>
							<td >@{{ row.position }}</td>
							<td class="text-center" width="100">
								<button v-on:click="editItem(row.person_id)" class="btn btn-sm btn-primary">
									<i class="fa fa-pencil"></i>
								</button>
								<button v-on:click="deleteItem(row.person_id)" class="btn btn-sm btn-danger">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>
</div>
@include('admin.modals.person_modal')
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
					persons: [],
					isEdit: 0,
					formTitle: '',
					form: {},
					errors: []
				}
			},
			created() {
				this.getPersons();
			},
			methods: {
				getPersons() {
					return axios.get(`${this.base_url}/admin/persons/get`)
					.then(({data}) => {
						this.persons = data;

						setTimeout(() => {
							$('#approver_table').DataTable();
						});
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				getPerson(person_id) {
					return axios.get(`${this.base_url}/admin/persons/get/${person_id}`)
					.then(({data}) => {
						this.form = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				saveItem() {
					if (this.isEdit) {
						axios.put(`${this.base_url}/admin/persons/put/${this.form.person_id}`, this.form)
						.then(({data}) => {
							this.getPersons();
							toastr.success('Person has been updated', 'Success', 'success');
							$('#approver_modal').modal('hide');
						})
						.catch((error) => {
							console.log(error.response);
							this.errors = error.response.data;
						});
					} 
					else {
						axios.post(`${this.base_url}/admin/persons/post`, this.form)
						.then(({data}) => {
							this.getPersons();
							toastr.success('New Person has been added', 'Success', 'success');
							this.resetForm();
						})
						.catch((error) => {
							console.log(error.response);
							this.errors = error.response.data;
						});
					}
				},
				deleteItem(person_id) {
					swal({
						title: "Delete this item?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((data) => {
						if (data) {
							axios.delete(`${this.base_url}//admin/persons/delete/${person_id}`)
							.then(({data}) => {
								this.getPersons();
								toastr.success('Person has been deleted', 'Success', 'success');
							})
							.catch((error) => {
								console.log(error.response);
							});
						}
					});
				},
				createItem() {
					this.isEdit = 0;
					this.formTitle = 'Add Person';
					this.errors = [];
					this.form = {};
					$('#approver_modal').modal('show');
				},
				editItem(person_id) {
					this.isEdit = 1;
					this.formTitle = 'Update Person';
					this.errors = [];
					this.getPerson(person_id);
					$('#approver_modal').modal('show');
				},
				resetForm() {
					this.errors = [];
					this.form = {};
				}
			}
		})
	document.querySelector('#persons_tab').setAttribute('class', 'active');
	</script>
@endpush