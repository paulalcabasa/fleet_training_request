@extends('layouts.guest_layout')

@push('styles')
	<link href="{{ url('public/libraries/css/bootstrap.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/css/bootstrap-select.min.css">
	<link href="https://fonts.googleapis.com/css?family=Raleway:300,400,500,700" rel="stylesheet" type="text/css">
	<link rel="stylesheet" href="{{ url('public/libraries/css/viewer.min.css') }}">
	<style>
		.raleway {
			color: #636b6f;
			font-family: 'Raleway', sans-serif;
			font-weight: 600;
		}

		.menu {
			width:170px; 
			text-align:center; 
			float:left; 
			position:relative
		}

		.menu_label {
			color:#FFFFFF; 
			float:left; 
			position:absolute; 
			top:20px; 
			left:150px;
		} 
	</style>
@endpush

@section('content')
<template>
	<v-stepper non-linear v-model="e1">
		<v-stepper-header>
			<v-stepper-step editable :complete="e1 > 1" step="1">I. Customer Information</v-stepper-step>
			<v-divider></v-divider>
			<v-stepper-step editable :complete="e1 > 2" step="2">II. Training Information</v-stepper-step>
			<v-divider></v-divider>
			<v-stepper-step editable :complete="e1 > 3" step="3">Program Offerings</v-stepper-step>
			<v-divider></v-divider>
			<v-stepper-step editable :complete="e1 > 4" step="4">Isuzu Models</v-stepper-step>
			<v-divider></v-divider>
			<v-stepper-step editable :complete="e1 > 5" step="5">Submit</v-stepper-step>
		</v-stepper-header>
	
		<v-stepper-items>
			<v-stepper-content step="1">
				@include('guest.customer_information')

				<pre>@{{ form }}</pre>
		
				<v-btn
				color="primary"
				v-on:click="step(2)"
				>
				Continue
				</v-btn>
		
			</v-stepper-content>
		
			<v-stepper-content step="2">
				@include('guest.training_information')
				<pre>@{{ form }}</pre>
				<v-btn
				color="primary"
				v-on:click="step(3)"
				>
				Continue
				</v-btn>
		
			</v-stepper-content>
		
			<v-stepper-content step="3">
				@include('guest.programs_offering')
				<pre>@{{ form }}</pre>
			</v-stepper-content>

			<v-stepper-content step="4">
				@include('guest.isuzu_models')
				<pre>@{{ form }}</pre>
			</v-stepper-content>

			<v-stepper-content step="5">
				@include('guest.submit_form')
				<pre>@{{ form }}</pre>
		
				<v-btn
				color="primary"
				v-on:click="submitDummyRequestData()"
				>
				Submit
				</v-btn>
		
			</v-stepper-content>
		</v-stepper-items>
	</v-stepper>
</template>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/js/bootstrap.min.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min.js"></script>
	<script src="{{ url('public/libraries/js/viewer.min.js') }}"></script>
	
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
					e1: 0,
					dialog: false,
					photo_gallery: false,
					drawer: true,
					participants: {},
					//
					step1: true,
					step2: false,
					step3: false,
					step4: false,
					step5: false,
					// Form
					form: {
						company_name: '',
						office_address: '',
						contact_person: '',
						email: '',
						contact_number: '',
						selling_dealer: [],
						position: '',
						contact_number: '',
						unit_models: [],
						
						training_date: '',
						training_participants: [],
						training_venue: [],
						training_address: '',
						training_program_id: 0,
						unit_model_id: 0,
					},
					unit_models: [],
					dealers: [],
					training_programs: [],
					images: '',
					selected_unit: 0
				}
			},
			props: {
				source: String
			},
			watch: {
				e1() {
					if (this.e1 == 1) {
						this.fetchDealers();
						this.fetchUnitModels();
					} else if (this.e1 == 2) {

					} else if (this.e1 == 3) {
						this.fetchTrainingPrograms();
					} else if (this.e1 == 4) {
						this.fetchUnitModels();
					} else if (this.e1 == 5) {

					}
				}
			},
			created() {
				this.e1 = 1;
				this.fetchUnitModels();
				this.fetchDealers();
			},
			mounted() {
				setTimeout(() => {
					$('.selectpicker').selectpicker();
					this.fetchDealers();
					this.fetchUnitModels();
				}, 500);
			},
			methods: {
				step(step) {
					this.e1 = step;
					if (step === 2) {
						this.step1 = true;
						this.step2 = true;
					}
					else if (step === 3) {
						this.step1 = true;
						this.step2 = true;
						this.step3 = true;
					}
					else if (step === 4) {
						this.step1 = true;
						this.step2 = true;
						this.step3 = true;
						this.step4 = true;
					}
					else if (step === 5) {
						this.step1 = true;
						this.step2 = true;
						this.step3 = true;
						this.step4 = true;
						this.step5 = true;
					}
				},
				remove(index) {
					this.form.training_participants.splice(index, 1);
				},
				add() {
					if (this.participants.participant != null && this.participants.quantity != null) {
						this.form.training_participants.push(this.participants);
						this.participants = {};
						this.dialog = false;
					}
				},
				others() {
					this.dialog = true;
				},
				fetchUnitModels() {
					axios.get(`${this.base_url}/guest/unit_models/get`)
					.then(({data}) => {
						this.unit_models = data;
					})
					.catch((error) => {
						console.log(error);
					});
				},
				fetchDealers() {
					axios.get(`${this.base_url}/guest/dealers/get`)
					.then(({data}) => {
						this.dealers = data;
					})
					.catch((error) => {
						console.log(error.response);
					});
				},
				fetchTrainingPrograms() {
					axios.get(`${this.base_url}/guest/training_programs/get`)
					.then(({data}) => {
						this.training_programs = data;
					})
					.catch((error) => {
						console.log(error);
					});
				},
				openGallery(training_program_id) {
					axios.get(`${this.base_url}/admin/gallery/get_images/${training_program_id}`)
					.then(({data}) => {
						this.images = data.images;
						this.photo_gallery = true;

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
				trainingProgramPicked(training_program_id) {
					this.form.training_program_id = training_program_id;

					swal({
						title: "Alright!",
						text: "You can now proceed to the next step.",
						icon: "success",
						buttons: ["I'll change it", "Okay, Proceed!"],
						closeOnClickOutside: false,
					})
					.then((res) => {
						if (res) this.step(4);
					});
				},
				unitModelPicked(unit_model_id) {
					this.form.unit_model_id = unit_model_id;

					swal({
						title: "Alright!",
						text: "You can now proceed to the next step.",
						icon: "success",
						buttons: ["I'll change it", "Okay, Proceed!"],
						closeOnClickOutside: false,
					})
					.then((res) => {
						if (res) this.step(5);
					});
				},
				submitDummyRequestData() {
					// var data = {
					// 	"company_name": "Sample Company",
					// 	"office_address": "isuzu binan",
					// 	"contact_person": "Prince Ivan Kent",
					// 	"email": "princeivankentmtiburcio@gmail.com",
					// 	"contact_number": "09467311489",
					// 	"selling_dealer": [
					// 		"GenCars|Alabang",
					// 		"GenCars|Batangas"
					// 	],
					// 	"position": "Web Developer",
					// 	"unit_models": [
					// 		"Bus",
					// 		"Crosswind",
					// 		"C&E Series",
					// 		"N Series"
					// 	],
					// 	"training_date": "2018-10-08",
					// 	"training_participants": [
					// 		{
					// 			"participant": "Fleet Head",
					// 			"quantity": "5"
					// 		},
					// 		{
					// 			"participant": "Driver",
					// 			"quantity": "1"
					// 		}
					// 	],
					// 	"training_venue": "Flee Customer",
					// 	"training_address": "Isuzu Philippines Corporation",
					// 	"training_program_id": 32,
					// 	"unit_model_id": 29
					// }

					axios.post(`${this.base_url}/guest/submit_request/post`, this.form)
					.then(({data}) => {
						console.log(data);
					})
					.catch((error) => {
						console.log(error.response);
					});
				}
			}
		})
	</script>
@endpush