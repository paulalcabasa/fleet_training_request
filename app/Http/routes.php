<?php

// Approver email urls
Route::get('approver/update_request/{approval_status_id}/{status}', 'ApproveRequestController@update_approval_request');


// ============== ADMINISTRATOR ================ //
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

Route::get('/admin/training_requests/get', 'TrainingRequestController@index');
Route::get('/admin/training_requests/get/{training_request_id}', 'TrainingRequestController@show');
Route::post('/admin/training_requests/store', 'TrainingRequestController@store');

// Approvers
Route::get('/admin/approvers/get', 'ApproverController@index');
Route::get('/admin/approvers/get/{approver_id}', 'ApproverController@show');
Route::post('/admin/approvers/post', 'ApproverController@store');
Route::put('/admin/approvers/put/{approver_id}', 'ApproverController@update');
Route::delete('/admin/approvers/delete/{approver_id}', 'ApproverController@destroy');

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
Route::put('/admin/unit_models/update/{unit_model_id}', 'UnitModelController@update');
Route::delete('/admin/unit_models/delete/{unit_model_id}', 'UnitModelController@delete');

// Training Programs
Route::get('/admin/training_programs/get', 'TrainingProgramController@index');
Route::get('/admin/training_programs/show/{training_program_id}', 'TrainingProgramController@show');
Route::post('/admin/training_programs/store', 'TrainingProgramController@store');
Route::put('/admin/training_programs/update/{training_program_id}', 'TrainingProgramController@update');
Route::delete('/admin/training_programs/delete/{training_program_id}', 'TrainingProgramController@delete');

// ============== GUEST ================ //
Route::get('/guest/unit_models/get', 'UnitModelController@index');
Route::get('/guest/training_programs/get', 'TrainingProgramController@index');
Route::get('/guest/dealers/get', 'DealerController@index');
Route::post('/guest/submit_request/post', 'TrainingRequestController@store');
Route::get('guest/schedules/get', 'ScheduleController@index');

Route::get('/guest/send_fake_email', 'FakeEmailTestsController@send');

// ============== OUTSIDE SYSTEM ================ //
Route::get('superior/approve/{approval_status_id}', 'SuperiorController@approve')->name('superior_approval');
Route::get('superior/disapprove/{approval_status_id}', 'SuperiorController@disapprove')->name('superior_disapproval');
Route::get('customer/confirm_request/{training_request_id}', 'RequestorController@confirm')->name('customer_confirmation');
Route::get('customer/cancellation_request/{training_request_id}', 'RequestorController@cancel')->name('customer_cancellation');
Route::get('customer/reschedule_request/{training_request_id}', 'RequestorController@reschedule')->name('customer_reschedule');

// ============== Views ================ //
Route::get('/', function() { return view('guest.home'); });
Route::get('admin', function() { return redirect()->route('training_requests'); });
Route::get('admin/training_requests', function() { return view('admin.training_requests'); })->name('training_requests');
Route::get('admin/dealers', function() { return view('admin.dealers'); })->name('dealers');
Route::get('admin/unit_models', function() { return view('admin.unit_models'); })->name('unit_models');
Route::get('admin/training_programs', function() { return view('admin.training_programs'); })->name('training_programs');
Route::get('admin/approvers', function() { return view('admin.approvers'); })->name('approvers');
Route::get('admin/trainors', function() { return view('admin.trainors'); })->name('trainors');
Route::get('admin/schedules', function() { return view('admin.schedules'); })->name('schedules');

// ============== Public Views ================ //
Route::get('guest/message', function() { return view('public_pages.message'); });