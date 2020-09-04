@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Unit Models</h1>
	</section>
	
	<section class="content container-fluid">
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border clearfix">
				<h3 class="box-title"><i class="fa fa-th-list"></i> List of Models</h3>
				<button
				v-on:click="createUnitModel"
				class="btn btn-sm btn-flat btn-default pull-right">
					<i class="fa fa-plus-circle"></i>
					ADD MODEL
				</button>
			</div>
			<div class="box-body">
			
				<table id="unit_model_table" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th class="text-center text-uppercase">Image</th>
							<th class="text-center text-uppercase">Sequence No</th>
							<th class="text-center text-uppercase">Model Name</th>
							<th class="text-center text-uppercase">Description</th>
							<th class="text-center text-uppercase">&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<tr v-for="(item, index) in items">
							<td width="10">
								<img :src="`{{url('public/images/unit_models')}}/${item.image}`" 
								class="img-thumbnail img-responsive"
								width="70" height="70"
								alt="">
							</td>
							<td class="text-center ">@{{ item.sequence_no }}</td>
							<td class="text-center text-primary text-bold">@{{ item.model_name }}</td>
							<td class="text-center">@{{ item.description }}</td>
							<td class="text-center" width="100">
								<button v-on:click="editUnitModel(item.unit_model_id, index)" class="btn btn-sm btn-primary">
									<i class="fa fa-pencil"></i>
								</button>
								<button v-on:click="deleteItem(item.unit_model_id, index)" class="btn btn-sm btn-danger">
									<i class="fa fa-trash"></i>
								</button>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</section>

	@include('admin.modals.unit_model_modal')
</div>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	<script src="{{ url('public/libraries/js/vue-tags-input.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
					bodyType: '',
					selectedBodyTypes: [],
					bodyTypes: [],
					emissionStandard: '',
					selectedEmissionStandards: [],
					emissionStandards: [],
					items: [],
					isEdit: 0,
					form: {},
					form_title: '',
					errors: [],
					image: '',
					image2: '',
					bodyTypeValidation: [{
						classes: 'avoid-item',
						rule: tag => !this.bodyTypes.some(bodyType => bodyType.text === tag.text),
						disableAdd: true,
					}],
					emissionStandardValidation : [{
						classes: 'avoid-item',
						rule: tag => !this.emissionStandards.some(emissionStandard => emissionStandard.text === tag.text),
						disableAdd: true,
					}]
				}
			},
			created() {
				this.getItems();
				this.getBodyTypes();
				this.getEmissionStandards();
			},
			methods: {
				onImageChange(e) {
					let files = e.target.files || e.dataTransfer.files;
					if (!files.length) return;
					this.createImage(files[0]);
				},
				createImage(file) {
					let reader = new FileReader();
					let vm = this;
					reader.onload = (e) => {
						vm.image = e.target.result;
					};
					reader.readAsDataURL(file);
				},
				dataTable() {
					setTimeout(() => {
						$('#unit_model_table').DataTable();
					});
				},
				getItems() {
					return axios.get(`${this.base_url}/admin/unit_models/get`)
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
				getItem(unit_model_id) {
					axios.get(`${this.base_url}/admin/unit_models/get/${unit_model_id}`)
					.then(res => {
						this.image2 = `{{ url('public/images/unit_models/${res.data.model.image}') }}`;
						this.form = res.data.model;
						this.selectedBodyTypes = [];
						res.data.body_types.map(bodyType => {
							this.selectedBodyTypes.push({
								id : bodyType.body_type.body_type_id,
								text : bodyType.body_type.name,
								tiClasses : ['ti-valid']
							});
						});
						this.selectedEmissionStandards = [];
						res.data.emission_standards.map(emissionStandard => {
							this.selectedEmissionStandards.push({
								id : emissionStandard.emission_standard.emission_standard_id,
								text : emissionStandard.emission_standard.name,
								tiClasses : ['ti-valid']
							});
						});

					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				storeItem() {
					this.form.image = this.image;
					this.form.bodyTypes = this.selectedBodyTypes;
					this.form.emissionStandards = this.selectedEmissionStandards;

					if (this.isEdit) return this.updateItem();
					
					return axios.post(`${this.base_url}/admin/unit_models/store`, this.form)
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
					axios.post(`${this.base_url}/admin/unit_models/update/${this.form.unit_model_id}`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully updated!');
						this.resetForm();
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				deleteItem(unit_model_id, index) {
					swal({
						title: "Delete this item?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							axios.delete(`${this.base_url}/admin/unit_models/delete/${unit_model_id}`)
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
				createUnitModel() {
					this.form_title = 'Add New Unit Model';
					this.isEdit = 0;
					this.resetForm();
					document.getElementById('image').value = '';
					$('#unit_model_modal').modal('show');
				},
				editUnitModel(unit_model_id) {
					this.form_title = 'Edit Unit Model';
					this.isEdit = 1;
					this.errors = [];
					this.image = '';
					document.getElementById('image').value = '';
					this.getItem(unit_model_id);
					$('#unit_model_modal').modal('show');
				},
				resetForm() {
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
					this.image = '';
					this.image2 = '';
					this.selectedBodyTypes = [];
					this.selectedEmissionStandards = [];
					document.getElementById('image').value = '';
				},
				getBodyTypes() {
					axios.get('body_type/all').then(res => {
						res.data.map( data => {
							this.bodyTypes.push({
								id : data.body_type_id,
								text: data.name
							});
						});
					}).catch(err => {
						alert("There is a problem encountered in loading the body types, please refresh the page.");
					});
				},
				getEmissionStandards() {
					axios.get('emission_standard/get').then(res => {
						res.data.map( data => {
							this.emissionStandards.push({
								id : data.emission_standard_id,
								text: data.name
							});
						});
					}).catch(err => {
						alert("There is a problem encountered in loading the body types, please refresh the page.");
					});
				}
			
			}
        })
		document.querySelector('#unit_model_tab').setAttribute('class', 'active');
	</script>
@endpush