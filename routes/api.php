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
});
