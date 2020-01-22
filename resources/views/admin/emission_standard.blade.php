@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Emission Standards</h1>
	</section>
	
	<section class="content container-fluid">
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border clearfix">
				<h3 class="box-title"><i class="fa fa-th-list"></i>List</h3>
				<button
				v-on:click="create"
				class="btn btn-sm btn-flat btn-default pull-right">
					<i class="fa fa-plus-circle"></i>
					ADD NEW
				</button>
			</div>
			<div class="box-body">
				<table id="datatable" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center text-uppercase">Name</th>
							<th class="text-center text-uppercase">Status</th>
							<th class="text-center text-uppercase">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in items">
							<td class="text-center ">@{{ item.name }}</td>
							<td class="text-center text-primary text-bold">@{{ item.status }}</td>
                            <td>
								<a href="#" v-on:click.prevent="editEmission(item.emission_standard_id, index)" style="margin-right:1em;">
									<i class="fa fa-pencil text-primary"></i>
								</a>
                            
							</td>
						</tr>
					</tbody>
				</table>
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
                        <input type="hidden" v-model="form.emission_standard_id">
                        <div class="form-row">
                            <div class="form-group">
                                <label for="model_name">Name</label>
                                <input type="text" class="form-control" id="name" v-model="form.name">
                                <span v-if="errors.name" class="text-danger">
                                    @{{ errors.name[0] }}
                                </span>
                            </div>
                            <div class="form-group">
                                <label for="description">Status</label>
                                <select name="" id="" class="form-control" v-model="form.status">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select> 
                                <span v-if="errors.status" class="text-danger">
                                    @{{ errors.status[0] }}
                                </span>
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
                        name : '',
                        status : 'active'
                    },
					form_title: '',
					errors: [],
					image: '',
					image2: '',
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
					return axios.get(`${this.base_url}/admin/emission_standard/get`)
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
					
					return axios.post(`${this.base_url}/admin/emission_standard/store`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully updated!');
						this.resetForm();
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				updateItem() {
					axios.put(`${this.base_url}/admin/emission_standard/update/${this.form.emission_standard_id}`, this.form)
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
					this.form_title = 'New';
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
					$('#form_modal').modal('show');
				},
				editEmission(emission_standard_id) {
					this.form_title = 'Edit';
					this.isEdit = 1;
					this.errors = [];
					this.getItem(emission_standard_id);
					$('#form_modal').modal('show');
				},
				resetForm() {
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
				}
			}
        })
		document.querySelector('#emission_tab').setAttribute('class', 'active');
	</script>
@endpush