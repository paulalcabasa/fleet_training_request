<?php
// header('Access-Control-Allow-Origin: *');
// header( 'Access-Control-Allow-Headers: Authorization, Content-Type');

Route::get('guest/dealers/get', 'Api\DealerController@index');
Route::get('guest/unit_models/get', 'Api\UnitModelController@index');

Route::get('emission_standards/get/{unit_model_id}', 'Api\EmissionStandardController@index');
Route::get('body_types/get', 'Api\BodyTypeController@index');
Route::get('body_types/get/{unit_model_id}', 'Api\BodyTypeController@index');

Route::get('guest/schedules/get', 'Api\ScheduleController@index');
Route::get('guest/training_programs/get', 'Api\TrainingProgramController@index');
Route::get('guest/gallery/get_images/{training_program_id}', 'Api\TrainingProgramController@get_images');
Route::get('guest/unit_models/get', 'Api\UnitModelController@index');
Route::get('unit_models', 'Api\UnitModelController@get_unit_models');
Route::get('special_trainings', 'Api\SpecialTrainingController@index');
Route::post('submit', 'Api\TrainingRequestController@store');
Route::get('admin/get-request/{training_request_id}','Api\TrainingRequestController@show');