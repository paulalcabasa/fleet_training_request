<?php

// Approver email urls
Route::get('approver/update_request/{approval_status_id}/{status}', 'ApproveRequestController@update_approval_request');

// ============== GUEST ================ //
Route::get('guest/gallery/get_images/{training_program_id}', 'TrainingProgramController@get_images');
Route::get('/guest/unit_models/get', 'UnitModelController@index');
Route::get('/guest/training_programs/get', 'TrainingProgramController@index');
Route::get('/guest/dealers/get', 'DealerController@index');
Route::post('/guest/submit_request/post', 'TrainingRequestController@store');
Route::get('guest/schedules/get', 'ScheduleController@index');
Route::get('guest/special_trainings/get', 'SpecialTrainingController@index');

Route::get('/guest/send_fake_email', 'FakeEmailTestsController@send');

// ============== OUTSIDE SYSTEM ================ //
Route::get('superior/approve/{approval_status_id}', 'SuperiorController@approve')->name('superior_approval');
Route::get('superior/disapprove/{approval_status_id}', 'SuperiorController@disapprove')->name('superior_disapproval');
Route::post('superior/disapprove_request', 'SuperiorController@disapprove_request');
Route::get('customer/confirm_request/{training_request_id}', 'RequestorController@confirm')->name('customer_confirmation');
Route::get('customer/cancellation_request/{training_request_id}', 'RequestorController@cancel')->name('customer_cancellation');
Route::post('customer/cancel_training_request/', 'RequestorController@cancel_training_request');
Route::get('customer/reschedule_request/{training_request_id}', 'RequestorController@reschedule')->name('customer_reschedule');

// ============== Public Views ================ //
// Route::get('/', function() { return view('guest.home'); });
Route::get('/', function() { return view('layouts.guest_layout'); });
Route::get('guest/message', function() { return view('public_pages.message'); });

// ============== Login ================ //
Route::get('admin/login/{employee_id}/{employee_no}/{full_name}/{section}', 'SessionLoginController@login');

// ============== Error ================ //
Route::get('/blocked_page', function() { return view('errors.blocked_page'); });

// ============== ADMINISTRATOR ================ //
Route::group(['middleware' => ['admin_guard']], function () {
    
    // Logout
    Route::get('admin/logout', 'SessionLoginController@logout');

    // Calendar
    Route::get('admin/calendar/events', 'CalendarController@events');
    Route::get('admin/calendar/events/{schedule_id}', 'CalendarController@event');
    Route::post('admin/calendar/events', 'CalendarController@save_event');
    Route::put('admin/calendar/events/{schedule_id}', 'CalendarController@update_event');
    Route::delete('admin/calendar/events/{schedule_id}', 'CalendarController@delete_event');

    // Designated Trainors
    Route::get('admin/designated_trainors/assigned_trainors/{training_request_id}', 'DesignatedTrainorController@assigned_trainors');
    Route::post('admin/designated_trainors/assign_trainor', 'DesignatedTrainorController@assign_trainor');
    Route::post('admin/designated_trainors/remove_trainor', 'DesignatedTrainorController@remove_trainor');

    // Special Offers
    Route::get('admin/special_trainings/get', 'SpecialTrainingController@index');
    Route::get('admin/special_trainings/get/{special_training_id}', 'SpecialTrainingController@show');
    Route::post('admin/special_trainings/post', 'SpecialTrainingController@store');
    Route::put('admin/special_trainings/put/{special_training_id}', 'SpecialTrainingController@update');
    Route::delete('admin/special_trainings/delete/{special_training_id}', 'SpecialTrainingController@delete');
    Route::get('admin/special_training_images/get/{special_training_id}', 'SpecialTrainingController@get_images');
    Route::post('admin/special_training_images/post', 'SpecialTrainingController@store_image');
    Route::delete('admin/special_training_images/delete/{special_training_image_id}', 'SpecialTrainingController@delete_image');

    // Disabling Dates
    Route::get('admin/schedules/get', 'ScheduleController@index');
    Route::get('admin/schedules/get/{schedule_id}', 'ScheduleController@show');
    Route::post('admin/schedules/store', 'ScheduleController@store');
    Route::put('admin/schedules/update/{schedule_id}', 'ScheduleController@update');
    Route::delete('admin/schedules/delete/{schedule_id}', 'ScheduleController@delete');

    // Reschedule
    Route::put('admin/training_requests/reschedule/{training_request_id}', 'RescheduleController@reschedule');

    // training_requests
    Route::get('/admin/training_requests_statuses', 'TrainingRequestController@training_requests_statuses');
    Route::get('/admin/approver_statuses/{training_request_id}', 'TrainingRequestController@approver_statuses');

    // Trainors
    Route::get('/admin/trainors/get', 'TrainorController@index');
    Route::get('/admin/trainors/get/{trainor_id}', 'TrainorController@show');
    Route::post('/admin/trainors/post', 'TrainorController@store');
    Route::put('/admin/trainors/put/{trainor_id}', 'TrainorController@update');
    Route::put('/admin/trainors/delete/{trainor_id}', 'TrainorController@delete');
    Route::put('/admin/trainors/undo_delete/{trainor_id}', 'TrainorController@undo_delete');
    Route::delete('/admin/trainors/permanent_delete/{trainor_id}', 'TrainorController@permanent_delete');

    // Request Approval
    Route::put('/admin/update_request/{training_request_id}', 'ApproveRequestController@update_request');
    Route::put('/admin/update_request_details', 'TrainingRequestController@update_request');

    Route::get('/admin/training_requests/get', 'TrainingRequestController@index');
    Route::get('/admin/training_requests/get/{training_request_id}', 'TrainingRequestController@show');
    Route::post('/admin/training_requests/store', 'TrainingRequestController@store');

    // Approvers
    Route::get('/admin/approvers/get', 'ApproverController@index');
    Route::get('/admin/approvers/get/{approver_id}', 'ApproverController@show');
    Route::post('/admin/approvers/post', 'ApproverController@store');
    Route::put('/admin/approvers/put/{approver_id}', 'ApproverController@update');
    Route::delete('/admin/approvers/delete/{approver_id}', 'ApproverController@destroy');

    // Persons
    Route::get('/admin/persons/get', 'PersonController@index');
    Route::get('/admin/persons/get/{person_id}', 'PersonController@show');
    Route::post('/admin/persons/post', 'PersonController@store');
    Route::put('/admin/persons/put/{person_id}', 'PersonController@update');
    Route::delete('/admin/persons/delete/{person_id}', 'PersonController@destroy');

    // Gallery
    Route::get('/admin/gallery/get_images/{training_program_id}', 'TrainingProgramController@get_images');
    Route::post('/admin/gallery/upload_image', 'TrainingProgramController@upload_image');

    // Dealers
    Route::get('/admin/dealers/get', 'DealerController@index');
    Route::get('/admin/dealers/get/{dealer_id}', 'DealerController@show');
    Route::post('/admin/dealers/store', 'DealerController@store');
    Route::put('/admin/dealers/update/{dealer_id}', 'DealerController@update');
    Route::delete('/admin/dealers/delete/{dealer_id}', 'DealerController@delete');

    // Unit Models
    Route::get('/admin/unit_models/get', 'UnitModelController@index');
    Route::get('/admin/unit_models/get/{unit_model_id}', 'UnitModelController@show');
    Route::post('/admin/unit_models/store', 'UnitModelController@store');
    Route::post('/admin/unit_models/update/{unit_model_id}', 'UnitModelController@update');
    Route::delete('/admin/unit_models/delete/{unit_model_id}', 'UnitModelController@delete');

    // Training Programs
    Route::get('/admin/training_programs/get', 'TrainingProgramController@index');
    Route::get('/admin/training_programs/show/{training_program_id}', 'TrainingProgramController@show');
    Route::post('/admin/training_programs/store', 'TrainingProgramController@store');
    Route::put('/admin/training_programs/update/{training_program_id}', 'TrainingProgramController@update');
    Route::delete('/admin/training_programs/delete/{training_program_id}', 'TrainingProgramController@delete');
    

    // Reports
    Route::post('report/xls_report_summary', 'ReportsController@xls_report_summary');

    // Emission Standards
    Route::get('/admin/emission_standard/get', 'EmissionController@index');
    Route::get('/admin/emission_standard/get/{emission_standard_id}', 'EmissionController@show');
    Route::post('/admin/emission_standard/store', 'EmissionController@store');
    Route::get('/admin/emission_standard/get/{emission_standard_id}', 'EmissionController@show');    
    Route::put('/admin/emission_standard/update/{emission_standard_id}', 'EmissionController@update');

    // Body Types
    Route::get('/admin/body_type/get', 'BodyTypeController@index');
    Route::get('/admin/body_type/all', 'BodyTypeController@getAll');
    Route::get('/admin/body_type/get/{emission_standard_id}', 'BodyTypeController@show');
    Route::post('/admin/body_type/store', 'BodyTypeController@store');
    Route::get('/admin/body_type/get/{emission_standard_id}', 'BodyTypeController@show');    
    Route::put('/admin/body_type/update/{emission_standard_id}', 'BodyTypeController@update');

    // Participants
    Route::get('/admin/participants/{training_request_id}', 'ParticipantController@index');
    Route::get('/admin/participants/get/{training_request_id}', 'ParticipantController@getParticipants');
    Route::post('/admin/participant/store', 'ParticipantController@store');
    Route::put('/admin/participant/update/{id}', 'ParticipantController@update');
    Route::delete('/admin/participant/delete/{id}', 'ParticipantController@destroy');
    

    // Training requests
    Route::post('/admin/reports/training_requests/get', 'ReportsController@getTrainingRequestSummary');

    // ============== Views ================ //
    Route::get('admin', function() { return redirect()->route('training_requests'); });
    Route::get('admin/training_requests', function() { return view('admin.training_requests'); })->name('training_requests');
    Route::get('admin/dealers', function() { return view('admin.dealers'); })->name('dealers');
    Route::get('admin/unit_models', function() { return view('admin.unit_models'); })->name('unit_models');
    Route::get('admin/training_programs', function() { return view('admin.training_programs'); })->name('training_programs');
    Route::get('admin/approvers', function() { return view('admin.approvers'); })->name('approvers');
    Route::get('admin/trainors', function() { return view('admin.trainors'); })->name('trainors');
    Route::get('admin/persons', function() { return view('admin.persons'); })->name('persons');
    Route::get('admin/schedules', function() { return view('admin.schedules'); })->name('schedules');
    Route::get('admin/special_trainings', function() { return view('admin.special_trainings'); })->name('special_trainings');
    Route::get('admin/calendar', function() { return view('admin.calendar'); })->name('calendar');
    Route::get('report/request_summary', function() { return view('reports.request_summary'); })->name('request_summary');
    Route::get('report/training_summary', function() { return view('reports.training_summary'); })->name('training_summary');
    Route::get('admin/emission_standards', function() { return view('admin.emission_standard'); })->name('emission_standards');
    Route::get('admin/body_types', function() { return view('admin.body_types'); })->name('body_types');
    

});