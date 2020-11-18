@extends('layouts.admin_layout')

@push('styles')
	<link rel="stylesheet" href="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ url('public/libraries/daterangepicker/daterangepicker.css') }}">
@endpush

@section('content')
<div v-cloak>
	<section class="content-header">
		<h1>Fleet Database</h1>
	</section>
	
	<section class="content container-fluid">
        <div class="row">
            <div class="col-md-6">
                <form method="post">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="box box-primary shadow-lg">
                        <div class="box-header with-border clearfix">
                            <h3 class="box-title"><i class="fa fa-th-list"></i>Training Summary</h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="text"  class="form-control datepicker" name="start_date"/>
                            </div>
                        
                        </div>
                        <div class="box-footer">
                            <button
                                class="btn btn-sm btn-flat btn-primary pull-right"
                                type="button"
                                @click="search">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-md-6"></div>
        </div>

        <div class="alert alert-danger" v-if="searchFlag && programsList.length == 0">No data found</div>
        <div class="card" style="margin-bottom:1rem;" v-if="programsList.length > 0">
            <div class="card-header">
                <h4>Programs</h4>
            </div>
            <div class="card-body">
                <ul class="list-group list-group-flush" v-for="(row, index) in programsList">
                    <li class="list-group-item"><a href="#" @click.prevent="currentProgram = row.program_title">@{{ row.program_title }}</a></li>
                </ul>
            </div>
        </div>

        <div class="card" v-if="currentProgram != ''">
            <div class="card-header">
                <h4>@{{ currentProgram }}</h4>
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Training Ref No.</th>
                            <th>Company</th>
                            <th>Training Date</th>
                            <th>Participant Name</th>
                            <th>Score</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(row, index) in programsData" v-if="row.program_title == currentProgram">
                            <td>@{{ row.training_request_id }}</td>
                            <td>@{{ row.company_name }}</td>
                            <td>@{{ row.training_date | formatDate }}</td>
                            <td>@{{ row.first_name }} @{{ row.last_name }}</td>
                            <td>@{{ row.total_score }}</td>
                            <td class="text-center text-bold" :class="getRemarksClass(row.total_score)">@{{ getRemarks(row.total_score) }}</td>
                        </tr>
                    </tbody>
                </table>
                
            </div>
        </div>
        

        <!--  -->
        

      
        
	</section>

   
</div>
@endsection

@push('scripts')
	<script src="{{ url('public/libraries/adminlte/jquery.dataTables.min.js') }}"></script>
	<script src="{{ url('public/libraries/adminlte/dataTables.bootstrap.min.js') }}"></script>
    <script src="{{ url('public/libraries/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('public/libraries/js/moment.js') }}"></script>
	<script>
		new Vue({
			el: '#app',
			data() {
				return {
                    form : {
                        start_date : '',
                        end_date : ''
                    },
                    programsList : [],
                    programsData : [],
                    currentProgram : '',
                    searchFlag : false
				}
			},
			created() {

            },
            computed : {
                // filteredItems() {
                //     return this.programsData.filter(item => {
                //         return item.type.indexOf('test') > -1
                //     })
                // }
            },
            filters : {
                formatDate(value){
                    return moment(String(value)).format('MM/DD/YYYY')
                }
            },
            mounted() {
                var self = this;
                $('.datepicker').daterangepicker({
                    "showDropdowns": true,
                }, function(start, end, label) {
                    self.form.start_date = start.format('YYYY-MM-DD');
                    self.form.end_date = end.format('YYYY-MM-DD');
                });
            },
			methods: {
                search(){
                    var self = this;
                    axios.post(`${this.base_url}/admin/reports/training_requests/get`, this.form)
                    .then(response => {
                        var groupBy = function (xs, key) {
                            return xs.reduce(function (rv, x) {
                                (rv[x[key]] = rv[x[key]] || []).push(x);
                                return rv;
                            }, {});
                        };
                        var programsList = [];
                        self.programsData = response.data;
                        var programsGrouped = groupBy(response.data, 'program_title');
                        const programs = Object.entries(programsGrouped);
                        for (const [program, count] of programs) {
                            programsList.push({
                                program_title: program,
                                count: count.length
                            });
                            console.log(program);
                        }

                        self.programsList = programsList;
                    }).catch(err => {
                      //  alert("There has been a problem with the network, please try again.");
                    }).finally(() => {
                        self.searchFlag = true;
                    });
                },
                getRemarks(total_score){
					if(total_score >= 0 && total_score <= 2.99){
						return 'NEEDS IMPROVEMENT';
					}
					else if(total_score >= 3.00 && total_score <= 3.99) {
						return 'FAIR';
					}
					else if(total_score >= 4.00 && total_score <= 5.00){
						return 'GOOD';
					}
				},
				getRemarksClass(total_score){
					if(total_score >= 0 && total_score <= 2.99){
						return 'bg-danger';
					}
					else if(total_score >= 3.00 && total_score <= 3.99) {
						return 'bg-warning';
					}
					else if(total_score >= 4.00 && total_score <= 5.00){
						return 'bg-success';
					}
				}
			}
        })
		document.querySelector('#training_summary_tab').setAttribute('class', 'active');
	</script>
@endpush