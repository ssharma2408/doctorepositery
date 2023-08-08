<?php

Route::controller(Api\V1\Admin\ClosedTimingApiController::class)->group(function(){
	Route::get('clinic-close-status/{clinic_id}', 'check_closed');
});

Route::controller(Api\V1\Admin\PatientApiController::class)->group(function(){
	Route::get('code/{code}', 'change_family');
});

Route::controller(Api\V1\Admin\ClinicApiController::class)->group(function(){
	Route::get('token_status/{clinic_id}', 'token_status');
});

Route::controller(Api\V1\Admin\AnnouncementApiController::class)->group(function(){
	Route::get('announcements/{clinic_id}', 'announcements');
});

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
	// Package
    Route::apiResource('packages', 'PackageApiController');

    // Clinic
    Route::post('clinics/media', 'ClinicApiController@storeMedia')->name('clinics.storeMedia');
    Route::apiResource('clinics', 'ClinicApiController');		
	Route::get('doctors/{clinic_id}', 'ClinicApiController@doctors');
	Route::get('doctors_timing/{clinic_id}', 'ClinicApiController@doctors_timing');	
	Route::get('token_status/{clinic_id}', 'ClinicApiController@token_status');
	Route::get('doctors/{clinic_id}/{doctor_id}', 'ClinicApiController@get_doctor');
	Route::get('clinic-timings/{clinic_id}', 'ClinicApiController@get_timings');
	Route::get('profile/{user_id}', 'ClinicApiController@profile');
	Route::post('update_profile', 'ClinicApiController@update_profile');

    // Staff
    Route::apiResource('staffs', 'StaffApiController');
	Route::get('staff_profile/{user_id}', 'StaffApiController@profile');
	Route::post('staff_update_profile', 'StaffApiController@update_profile');

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
	Route::get('search_patients/{clinic_id}/{doctor_id}/{search_term}', 'PatientApiController@search_patients');
	Route::get('patient_family/{family_id}', 'PatientApiController@index');
	Route::post('sendsms', 'PatientApiController@sendsms');
    Route::apiResource('patients', 'PatientApiController');
	Route::get('patient_profile/{user_id}', 'PatientApiController@profile');
	Route::post('patient_update_profile', 'PatientApiController@update_profile');
	
/* 	Route::get('generate-shorten-link', 'ShortLinkController@index');
	Route::post('generate-shorten-link', 'ShortLinkController@store')->name('generate.shorten.link.post');
	   
	Route::get('{code}', 'ShortLinkController@shortenLink')->name('shorten.link'); */

    // Patient History	
    Route::post('patient-histories/media', 'PatientHistoryApiController@storeMedia')->name('patient-histories.storeMedia');	
    Route::apiResource('patient-histories', 'PatientHistoryApiController');

    // Token
	Route::get('tokens/{clinic_id}/{doctor_id}', 'TokenApiController@get_patients');
	Route::get('check_status/{clinic_id}/{doctor_id}/{slot_id}/{patient_id}', 'TokenApiController@check_status');
	Route::get('refresh_status/{clinic_id}/{doctor_id}/{slot_id}/{patient_id}', 'TokenApiController@refresh_status');
	Route::post('update_token', 'TokenApiController@update_token');
	Route::post('create_token', 'TokenApiController@create_token');
    Route::apiResource('tokens', 'TokenApiController');
	Route::get('refresh_token/{slot_id}', 'TokenApiController@refresh_token');
	
	//Pages
	Route::get('pages/{clinic_id}', 'ContentPageApiController@index');
	
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){
	Route::post('patient_register', 'register');
    Route::post('login', 'login');
	Route::post('patientlogin', 'generate');
	Route::post('patientloginwithotp', 'loginWithOtp');
});