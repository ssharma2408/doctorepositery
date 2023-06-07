<?php

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'Api\V1\Admin', 'middleware' => ['auth:sanctum']], function () {
    // Package
    Route::apiResource('packages', 'PackageApiController');

    // Clinic
    Route::post('clinics/media', 'ClinicApiController@storeMedia')->name('clinics.storeMedia');
    Route::apiResource('clinics', 'ClinicApiController');

    // Doctor
    Route::apiResource('doctors', 'DoctorApiController');

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

    // Closed Timing
    Route::apiResource('closed-timings', 'ClosedTimingApiController');

    // Domain
    Route::apiResource('domains', 'DomainApiController');
});

Route::controller(Api\V1\Admin\RegisterController::class)->group(function(){
	Route::post('register', 'register');
    Route::post('login', 'login');
});	
