@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>
			Dealers
		</h1>
	</section>
	
	<section class="content container-fluid">
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border clearfix">
				<h3 class="box-title"><i class="fa fa-th-list"></i> List of Dealers</h3>
				<button 
				v-on:click="createDealer"
				class="btn btn-sm btn-flat btn-default pull-right">
					<i class="fa fa-plus-circle"></i>
					ADD DEALER
				</button>
			</div>
			<div class="box-body">
				<table id="dealer_table" class="table table-bordered table-hover">
					<thead>
					<tr>
						<th class="text-center text-uppercase">#</th>
						<th class="text-center text-uppercase">Dealer</th>
						<th class="text-center text-uppercase">Branch</th>
						<th class="text-center text-uppercase">Email</th>
						<th class="text-center text-uppercase">Actions</th>
					</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in items">
							<td class="text-center" width="10">@{{ index+1 }}</td>
							<td class="text-center">@{{ item.dealer }}</td>
							<td class="text-center">@{{ item.branch }}</td>
							<td class="text-center">@{{ item.email }}</td>
							<td class="text-center" width="100">
								<button v-on:click="editDealer(item.dealer_id, index)" class="btn btn-sm btn-primary">
									<i class="fa fa-pencil"></i>
								</button>
								<button v-on:click="deleteItem(item.dealer_id, index)" class="btn btn-sm btn-danger">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	@include('admin.modals.dealer_modal')
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
					form: {},
					form_title: '',
					errors: []
				}
			},
			created() {
				this.getItems();
			},
			methods: {
				dataTable() {
					setTimeout(() => {
						$('#dealer_table').DataTable();
					});
				},
				getItems() {
					return axios.get(`${this.base_url}/admin/dealers/get`)
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
				getItem(dealer_id) {
					axios.get(`${this.base_url}/admin/dealers/get/${dealer_id}`)
					.then(({data}) => {
						this.form = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				storeItem() {
					if (this.isEdit) return this.updateItem();

					return axios.post(`${this.base_url}/admin/dealers/store`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully added!');
					})
					.catch((error) => {
						this.errors = error.response.data;
						console.log(error.response);
					});
				},
				updateItem() {
					axios.put(`${this.base_url}/admin/dealers/update/${this.form.dealer_id}`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully updated!');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				deleteItem(dealer_id, index) {
					swal({
						title: "Delete this item?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							axios.delete(`${this.base_url}/admin/dealers/delete/${dealer_id}`)
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
				// --------
				createDealer() {
					this.form_title = 'Add New Dealer';
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
					$('#dealer_modal').modal('show');
				},
				editDealer(dealer_id) {
					this.form_title = 'Edit Dealer';
					this.isEdit = 1;
					this.errors = [];
					this.getItem(dealer_id);
					$('#dealer_modal').modal('show');
				}
			}
        })
		document.querySelector('#dealer_tab').setAttribute('class', 'active');
	</script>
@endpush