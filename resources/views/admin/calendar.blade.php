@extends('layouts.admin_layout') 

@push('styles')
    <link rel="stylesheet" href="{{ url('public/libraries/full-calendar/fullcalendar.min.css') }}">
    <style>
        .btn {
            border: none;
        }
    </style>
@endpush

@section('content')
    <div v-cloak>
        <section class="content-header">
            <h1>
                Calendar
            </h1>
            <ol class="breadcrumb">
                <li>
                    <button
                    v-on:click="createSchedule(null)"
                    class="btn btn-flat btn-success pull-right" style="margin-top: -8px; border:none;">
                        <i class="fa fa-plus-circle"></i>&nbsp;
                        CREATE SCHEDULE
                    </button>
                </li>
            </ol>
        </section>

        <section class="content container-fluid">
            <div class="box box-default sub-content shadow-lg">
                <div class="box-body">
                    {{-- {!! $calendar->calendar() !!} --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div id="calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    @include('admin.modals.scheduler_modal')
@endsection

@push('scripts')
    {{-- {!! $calendar->script() !!} --}}
    <script src="{{ url('public/libraries/full-calendar/fullcalendar.min.js') }}"></script>
    <script>
        $('#calendar_tab').addClass('active');
        var vm = new Vue({
            el: '#app',
            data() {
                return {
                    isEdit: 0,
                    events: [],
                    form: {
                        start_date: '',
                        end_date: '',
                        reason: null
                    },
                }
            },
            created() {
                this.getEvents();
            },
            methods: {
                reason: function(training_request) {
                    if (training_request) {
                        return training_request.company_name;
                    }
                },
                getEvents: function() {
                    axios.get(`${this.base_url}/admin/calendar/events`)
                    .then(({data}) => {
                    
                        var events = [];
                        data.forEach(element => {
                            events.push({
                                schedule_id: element.schedule_id,
                                title      : 
                                    element.training_request != null ? 
                                    element.training_request.company_name :
                                    element.reason,
                                start      : element.start_date,
                                end        : element.end_date + ' 23:59:59',
                                color: element.training_request != null ? 
                                    '#00A65A' :
                                    '#3A87AD',
                                allDay: false
                            });
                        });
                        
                        this.events = events;

                        $('#calendar').fullCalendar('destroy');
                        return this.displayEvents();
                    })
                    .catch((error) => {
                        console.log(error.response);
                    });
                },
                createSchedule: function(date) {
                    this.form = {
                        start_date: date,
                        end_date: '',
                        reason: null
                    }
                    this.isEdit = 0;
                    $('#scheduler_modal').modal('show');
                },
                viewSchedule: function(schedule_id) {
                    this.isEdit = 1;
                    axios.get(`${this.base_url}/admin/calendar/events/${schedule_id}`)
                    .then(({data}) => {
                        data.reason = data.training_request != null ?
                                     data.training_request.company_name  :
                                     data.reason;

                        this.form = data;
                        $('#scheduler_modal').modal('show');
                    })
                    .catch((error) => {
                        console.log(error.response);
                        swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
                    });
                },
                saveSchedule: function() {
                    if (this.isEdit == 1) {
                        return axios.put(`${this.base_url}/admin/calendar/events/${this.form.schedule_id}`, this.form)
                            .then(({data}) => {
                                $('#scheduler_modal').modal('hide');
                                this.form = {};
                                this.getEvents();
                                swal('Success!', 'Schedule has been updated.', 'success', {timer:4000,button:false});
                            })
                            .catch((error) => {
                                console.log(error.response);
                                swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
                            });
                    }

                    return axios.post(`${this.base_url}/admin/calendar/events`, this.form)
                        .then(({data}) => {
                            if (data) {
                                this.form = {};
                                this.getEvents();
                                swal('Success!', 'New Schedule has been created.', 'success', {timer:4000,button:false});
                            }
                        })
                        .catch((error) => {
                            console.log(error.response);
                            swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
                        });
                },
                deleteSchedule: function(schedule_id) {
                    swal({
                        title: "Warning!",
                        text: "Do you want to delete this schedule?",
                        icon: "warning",
                        buttons: {
                            cancel: true,
                            confirm: 'Proceed'
                        },
                        closeOnClickOutside: false
                    })
                    .then((res) => {
                        if (res) {
                            axios.delete(`${this.base_url}/admin/calendar/events/${schedule_id}`)
                            .then(({data}) => {
                                if (data) {
                                    this.form = {};
                                    $('#scheduler_modal').modal('hide');
                                    this.getEvents();
                                    swal('Success!', 'Schedule has been deleted.', 'success', {timer:4000,button:false});
                                }
                            })
                            .catch((error) => {
                                console.log(error.response);
                                swal('Ooops!', 'Something went wrong.', 'error', {timer:4000,button:false});
                            });
                        }
                    });
                },
                displayEvents: function() {
                    $('#calendar').fullCalendar({
                        displayEventTime: false,
                        allDayDefault: true,
                        navLinks: true,
                        editable: true,
                        header: { 
                            left: 'today, prev,next',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        events: this.events,
                        eventClick: function(event) {
                            // alert(event.title + " was dropped on " + event.start.format() + ' to ' +  event.end.format());
                            vm.viewSchedule(event.schedule_id);
                        },
                        dayClick: function(date, jsEvent, view) {
                            vm.createSchedule(date.format()); // pass the date
                        },

                        // Edit
                        eventDrop: function(event, delta, revertFunc) {
                            alert(event.title + " was dropped on " + event.start.format() + ' to ' +  event.end.format());

                            // if (!confirm("Are you sure about this change?")) {
                            //     revertFunc();
                            // }
                        },
                        eventResize: function(event, delta, revertFunc) {
                            alert(event.title + " end is now " + event.end.format());

                            // if (!confirm("is this okay?")) {
                            //     revertFunc();
                            // }
                        }
                    });
                },
            }
        });
    </script>
@endpush