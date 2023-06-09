<?php

Route::controller(Api\V1\Admin\ClosedTimingApiController::class)->group(function(){
	Route::get('clinic-close-status/{clinic_id}', 'check_closed');
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
	// Package
    Route::apiResource('packages', 'PackageApiController');

    // Clinic
    Route::post('clinics/media', 'ClinicApiController@storeMedia')->name('clinics.storeMedia');
    Route::apiResource('clinics', 'ClinicApiController');		
	Route::get('doctors/{clinic_id}', 'ClinicApiController@doctors');
	Route::get('doctors_timing/{clinic_id}', 'ClinicApiController@doctors_timing');
	Route::get('doctors/{clinic_id}/{doctor_id}', 'ClinicApiController@get_doctor');
	Route::get('clinic-timings/{clinic_id}', 'ClinicApiController@get_timings');

    // Staff
    Route::apiResource('staffs', 'StaffApiController');

    // Content Category
    Route::apiResource('content-categories', 'ContentCategoryApiController');

    // Content Tag
    Route::apiResource('content-tags', 'ContentTagApiController');

    // Content Page
    Route::post('content-pages/media', 'ContentPageApiController@storeMedia')->name('content-pages.storeMedia');
    Route::apiResource('content-pages', 'ContentPageApiController');

    // Timing
    Route::apiResource('timings', 'TimingApiController');
	Route::post('timings-save', 'TimingApiController@save');

    // Closed Timing
	Route::get('closed-timings/{user_id}', 'ClosedTimingApiController@get_closed_day');
    Route::apiResource('closed-timings', 'ClosedTimingApiController');
	Route::post('closed-timings-save', 'ClosedTimingApiController@save');
	
    // Domain
    Route::apiResource('domains', 'DomainApiController');

    // Patient
	Route::get('patients/{clinic_id}/{doctor_id}', 'PatientApiController@get_patients');
    Route::apiResource('patients', 'PatientApiController');

    // Patient History	
    Route::post('patient-histories/media', 'PatientHistoryApiController@storeMedia')->name('patient-histories.storeMedia');	
    Route::apiResource('patient-histories', 'PatientHistoryApiController');

    // Token
	Route::get('tokens/{clinic_id}/{doctor_id}', 'TokenApiController@get_patients');
	Route::get('check_status/{clinic_id}/{doctor_id}/{patient_id}', 'TokenApiController@check_status');
	Route::get('refresh_status/{clinic_id}/{doctor_id}/{slot_id}/{patient_id}', 'TokenApiController@refresh_status');
	Route::post('update_token', 'TokenApiController@update_token');
    Route::apiResource('tokens', 'TokenApiController');
	
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){
	Route::post('patient_register', 'register');
    Route::post('login', 'login');
	Route::post('patientlogin', 'generate');
	Route::post('patientloginwithotp', 'loginWithOtp');
});