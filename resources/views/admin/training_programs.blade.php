@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ url('public/libraries/summernote-master/dist/summernote.min.css') }}">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css">

	<link rel="stylesheet" href="{{ url('public/libraries/css/viewer.min.css') }}">
	<style>
		.raleway {
			color: #636b6f;
			font-family: 'Raleway', sans-serif;
			font-weight: 600;
		}
	</style>
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Training Programs</h1>
	</section>
	
	<section class="content container-fluid">
		<div class="box box-primary shadow-lg">
			<div class="box-header with-border clearfix">
				<h3 class="box-title"><i class="fa fa-th-list"></i> List of Training Programs</h3>
				<button 
				v-on:click="createTrainingProgram"
				class="btn btn-sm btn-flat btn-default pull-right">
					<i class="fa fa-plus-circle"></i>
					ADD PROGRAM
				</button>
			</div>
			<div class="box-body">
				<div class="form-row">
					<div v-for="(item, index) in items" 
					class="col-md-4">
						<div class="card raleway shadow" style="min-height: 220px; margin-bottom: 12px;">
							<div class="card-header bg-white">
								<div class="row">
									<div class="col-md-7">
										<h4 class="card-title text-bold">
											<span class="text-primary">@{{ item.program_title }}</span>
										</h4>
									</div>
									<div class="col-md-5 clearfix mt-3">
										<button 
										v-on:click="deleteItem(item.training_program_id, index)"
										class="btn btn-sm btn-default pull-right ml-3" 
										style="margin-top: -10px; margin-right: -4px;">
											<i class="fa fa-trash"></i>
										</button>
										<button 
										v-on:click="editTrainingProgram(item.training_program_id)"
										class="btn btn-sm btn-default pull-right ml-3" 
										style="margin-top: -10px; margin-right: -4px;">
											<i class="fa fa-pencil"></i>
										</button>
										<button 
										v-on:click="openGallery(item.training_program_id)"
										class="btn btn-sm btn-default pull-right" 
										style="margin-top: -10px; margin-right: -4px;">
											<i class="fa fa-image"></i>
										</button>
									</div>
								</div>
								<small style="color: #9C9D9E" v-html="item.description"></small>
							</div>
							<ul class="list-group list-group-flush">
								<li v-for="(item, index) in item.program_features"
								class="list-group-item"><i class="fa fa-caret-right mr-2"></i> @{{ item.feature }}</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer"></div>
		</div>
		 
	</section>

	@include('admin.modals.training_program_modal')
	@include('admin.modals.gallery_modal')
</div>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
	<script src="../node_modules/@ckeditor/ckeditor5-build-classic/build/ckeditor.js"></script>
	<script src="../node_modules/@ckeditor/ckeditor5-vue/dist/ckeditor.js"></script>
	<script src="{{ url('public/libraries/js/viewer.min.js') }}"></script>
	<script>
		
		Vue.use( CKEditor );

		new Vue({
			el: '#app',
			data() {
				return {
					items: [],
					isEdit: 0,
					form: {},
					form_title: '',
					errors: [],
					features: [],
					program_feature_ids: [],
					training_program_id: 0,
					image: '',
					images: [],
					editor: ClassicEditor,
					editorData: '<p>Content of the editor.</p>',
					editorConfig: {
						// The configuration of the editor.
					}
				}
			},
			created() {
				this.getItems();
			},
			mounted(){
				var self = this;
				/* $('#training_program_modal').on('shown.bs.modal', function() {
					$("#description").summernote('destroy');
					$("#description").summernote().on("summernote.change", function (e) {   // callback as jquery custom event 
						self.form.description = $(this).val();
					});
					$('#description').summernote('code', self.form.description);
				}); */
			},	
			methods: {
				dataTable() {
					setTimeout(() => {
						$('#training_program_table').DataTable();
					});
				},
				getItems() {
					return axios.get(`${this.base_url}/admin/training_programs/get`)
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
				getItem(training_program_id) {
					axios.get(`${this.base_url}/admin/training_programs/show/${training_program_id}`)
					.then(({data}) => {
						this.form = data;
						this.features = data.program_features;
					//	$('#description').eq(1).summernote('destroy');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				storeItem() {
					if (this.isEdit) return this.updateItem();

					this.form.features = this.features;
					return axios.post(`${this.base_url}/admin/training_programs/store`, this.form)
						.then(({data}) => {
							this.getItems();
							this.resetForm();
							toastr.success('Successfully added!');
						})
						.catch((error) => {
							this.errors = error.response.data;
							console.log(error.response);
						});
				},
				updateItem() {
					this.form.program_feature_ids = this.program_feature_ids;
					axios.put(`${this.base_url}/admin/training_programs/update/${this.form.training_program_id}`, this.form)
					.then(({data}) => {
						this.getItems();
						toastr.success('Successfully updated!');
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				deleteItem(training_program_id, index) {
					swal({
						title: "Delete this item?",
						icon: "warning",
						buttons: true,
						dangerMode: true,
					})
					.then((willDelete) => {
						if (willDelete) {
							axios.delete(`${this.base_url}/admin/training_programs/delete/${training_program_id}`)
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
				resetForm() {
					this.features = [];
					this.form = [];
					this.errors = [];
				},
				// --------
				createTrainingProgram() {
					this.form_title = 'Add New Training Program';
					this.isEdit = 0;
					this.errors = [];
					this.form = {};
					this.features = [];
					$('#training_program_modal').modal('show');
				},
				editTrainingProgram(training_program_id) {
					this.form_title = 'Edit Training Program';
					this.isEdit = 1;
					this.errors = [];
					this.features = [];
					this.getItem(training_program_id);
					$('#training_program_modal').modal('show');
				},
				storeFeature() {
					if (this.form.feature) {
						this.features.push({feature: this.form.feature});
						this.form.feature = '';
					}
				},
				removeFeature(program_feature_id, index) {
					this.program_feature_ids.push(program_feature_id);
					this.features.splice(index, 1);
				},
				openGallery(training_program_id) {
					this.training_program_id = training_program_id;
					axios.get(`${this.base_url}/admin/gallery/get_images/${training_program_id}`)
					.then(({data}) => {
						this.images = data.images;
						$('#gallery_modal').modal('show');

						if (this.images.length > 0) {
							setTimeout(() => {
								var viewer = new Viewer(document.getElementById('images'));
								viewer.update();
							});
						}
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
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
				uploadImage() {
					var uri = `${this.base_url}/admin/gallery/upload_image`;
					axios.post(uri, {
						image: this.image,
						training_program_id: this.training_program_id
					})
					.then(({data}) => {
						this.updateImages();
						toastr.success('Image Successfully saved!');
					})
					.catch((error) => {
						this.errors = error.response.data;
						console.log(error.response);
					});
				},
				updateImages() {
					axios.get(`${this.base_url}/admin/gallery/get_images/${this.training_program_id}`)
					.then(({data}) => {
						this.images = data.images;
						if (this.images.length > 0) {
							setTimeout(() => {
								var viewer = new Viewer(document.getElementById('images'));
								viewer.update();
							});
						}
					})
					.catch((error) => {
						console.log(error.response);
					});
				}
			}
        })
		document.querySelector('#training_program_tab').setAttribute('class', 'active');
	</script>
@endpush