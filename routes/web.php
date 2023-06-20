<?php

Route::redirect('/', '/login');
Route::get('/home', function () {
    if (session('status')) {
        return redirect()->route('admin.home')->with('status', session('status'));
    }

    return redirect()->route('admin.home');
});

Auth::routes(['register' => false]);

Route::group(['prefix' => 'admin', 'as' => 'admin.', 'namespace' => 'Admin', 'middleware' => ['auth']], function () {
    Route::get('/', 'HomeController@index')->name('home');
    // Permissions
    Route::delete('permissions/destroy', 'PermissionsController@massDestroy')->name('permissions.massDestroy');
    Route::resource('permissions', 'PermissionsController');

    // Roles
    Route::delete('roles/destroy', 'RolesController@massDestroy')->name('roles.massDestroy');
    Route::resource('roles', 'RolesController');

    // Users
    Route::delete('users/destroy', 'UsersController@massDestroy')->name('users.massDestroy');
    Route::resource('users', 'UsersController');

    // Package
    Route::delete('packages/destroy', 'PackageController@massDestroy')->name('packages.massDestroy');
    Route::resource('packages', 'PackageController');

    // Clinic
    Route::delete('clinics/destroy', 'ClinicController@massDestroy')->name('clinics.massDestroy');
    Route::post('clinics/media', 'ClinicController@storeMedia')->name('clinics.storeMedia');
    Route::post('clinics/ckmedia', 'ClinicController@storeCKEditorImages')->name('clinics.storeCKEditorImages');
    Route::resource('clinics', 'ClinicController');

    // Staff
    Route::delete('staffs/destroy', 'StaffController@massDestroy')->name('staffs.massDestroy');
    Route::resource('staffs', 'StaffController');

    // Content Category
    Route::delete('content-categories/destroy', 'ContentCategoryController@massDestroy')->name('content-categories.massDestroy');
    Route::resource('content-categories', 'ContentCategoryController');

    // Content Tag
    Route::delete('content-tags/destroy', 'ContentTagController@massDestroy')->name('content-tags.massDestroy');
    Route::resource('content-tags', 'ContentTagController');

    // Content Page
    Route::delete('content-pages/destroy', 'ContentPageController@massDestroy')->name('content-pages.massDestroy');
    Route::post('content-pages/media', 'ContentPageController@storeMedia')->name('content-pages.storeMedia');
    Route::post('content-pages/ckmedia', 'ContentPageController@storeCKEditorImages')->name('content-pages.storeCKEditorImages');
    Route::resource('content-pages', 'ContentPageController');

    // Timing
    Route::delete('timings/destroy', 'TimingController@massDestroy')->name('timings.massDestroy');
    Route::resource('timings', 'TimingController');

    // Closed Timing
    Route::delete('closed-timings/destroy', 'ClosedTimingController@massDestroy')->name('closed-timings.massDestroy');
    Route::resource('closed-timings', 'ClosedTimingController');

    // Domain
    Route::delete('domains/destroy', 'DomainController@massDestroy')->name('domains.massDestroy');
    Route::resource('domains', 'DomainController');

    // Patient
    Route::delete('patients/destroy', 'PatientController@massDestroy')->name('patients.massDestroy');
    Route::resource('patients', 'PatientController');

    // Patient History
    Route::delete('patient-histories/destroy', 'PatientHistoryController@massDestroy')->name('patient-histories.massDestroy');
    Route::post('patient-histories/media', 'PatientHistoryController@storeMedia')->name('patient-histories.storeMedia');
    Route::post('patient-histories/ckmedia', 'PatientHistoryController@storeCKEditorImages')->name('patient-histories.storeCKEditorImages');
    Route::resource('patient-histories', 'PatientHistoryController');

    // Token
    Route::delete('tokens/destroy', 'TokenController@massDestroy')->name('tokens.massDestroy');
    Route::resource('tokens', 'TokenController');

    // Opening Hours
    Route::delete('opening-hours/destroy', 'OpeningHoursController@massDestroy')->name('opening-hours.massDestroy');
    Route::resource('opening-hours', 'OpeningHoursController');
	Route::post('opening-hours-save', 'OpeningHoursController@save')->name('opening.hours.save');	
});
Route::group(['prefix' => 'profile', 'as' => 'profile.', 'namespace' => 'Auth', 'middleware' => ['auth']], function () {
    // Change password
    if (file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php'))) {
        Route::get('password', 'ChangePasswordController@edit')->name('password.edit');
        Route::post('password', 'ChangePasswordController@update')->name('password.update');
        Route::post('profile', 'ChangePasswordController@updateProfile')->name('password.updateProfile');
        Route::post('profile/destroy', 'ChangePasswordController@destroy')->name('password.destroyProfile');
    }
});
