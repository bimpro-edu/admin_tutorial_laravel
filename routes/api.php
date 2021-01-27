<?php

Route::post('login', 'Auth\AuthController@login');
Route::post('login/social', 'Auth\SocialLoginController@index');
Route::post('logout', 'Auth\AuthController@logout');

Route::group(['middleware' => 'registration'], function () {
    Route::post('register', 'Auth\RegistrationController@index');
    Route::post('register/verify-email/{token}', 'Auth\RegistrationController@verifyEmail');

    // Candidate
    Route::post('candidate/register', 'Auth\RegistrationController@index')->name('candidate.register');
});

Route::group(['middleware' => 'password-reset'], function () {
    Route::post('password/remind', 'Auth\Password\RemindController@index');
    Route::post('password/reset', 'Auth\Password\ResetController@index');

    // For candidate
    Route::post('candidate/password/remind', 'Auth\Password\RemindController@index')
        ->name('candidate.password.remind');
    Route::post('candidate/password/reset', 'Auth\Password\ResetController@index')
        ->name('candidate.password.reset');
});
Route::post('password/change', 'Auth\Password\UpdateController@index');
Route::post('candidate/password/change', 'Auth\Password\UpdateController@index')
    ->name('candidate.password.change');

Route::get('stats', 'StatsController@index');

Route::get('me', 'Profile\DetailsController@index');
Route::patch('me/details', 'Profile\DetailsController@update');
Route::patch('me/details/auth', 'Profile\AuthDetailsController@update');
Route::put('me/avatar', 'Profile\AvatarController@update');
Route::delete('me/avatar', 'Profile\AvatarController@destroy');
Route::put('me/avatar/external', 'Profile\AvatarController@updateExternal');
Route::get('me/sessions', 'Profile\SessionsController@index');
Route::put('me/update/location', 'Profile\DetailsController@updateLocation');

Route::group(['middleware' => 'two-factor'], function () {
    Route::put('me/2fa', 'Profile\TwoFactorController@update');
    Route::post('me/2fa/verify', 'Profile\TwoFactorController@verify');
    Route::delete('me/2fa', 'Profile\TwoFactorController@destroy');
});

Route::resource('users', 'Users\UsersController', [
    'except' => 'create'
]);

Route::put('users/{user}/avatar', 'Users\AvatarController@update');

Route::put('users/{user}/avatar/external', 'Users\AvatarController@updateExternal');
Route::delete('users/{user}/avatar', 'Users\AvatarController@destroy');

Route::group(['middleware' => 'two-factor'], function () {
    Route::put('users/{user}/2fa', 'Users\TwoFactorController@update');
    Route::post('users/{user}/2fa/verify', 'Users\TwoFactorController@verify');
    Route::delete('users/{user}/2fa', 'Users\TwoFactorController@destroy');
});

Route::get('users/{user}/activity', 'Activity\UserActivityController@index');
Route::get('users/{user}/sessions', 'Users\SessionsController@index');

Route::get('/sessions/{session}', 'SessionsController@show');
Route::delete('/sessions/{session}', 'SessionsController@destroy');

Route::get('/activity', 'Activity\ActivityController@index');

Route::resource('roles', 'Authorization\RolesController', [
    'except' => 'create'
]);
Route::get("roles/{role}/permissions", 'Authorization\RolePermissionsController@show');
Route::put("roles/{role}/permissions", 'Authorization\RolePermissionsController@update');

Route::resource('permissions', 'Authorization\PermissionsController', [
    'except' => 'create'
]);

Route::get('/settings', 'SettingsController@index');

Route::get('/countries', 'CountriesController@index');

Route::get('/stats/activity', 'Activity\StatsController@show');

Route::group(['middleware' => 'auth'], function () {
    Route::apiResource('/announcements', 'Announcement\AnnouncementsController')
        ->names('announcements.api');

    Route::post('announcements/read', 'Announcement\ReadAnnouncementsController@index');
});

Route::post('recruiter/login', 'Auth\AuthController@login')->name('recruiter.login');
Route::post('candidate/login', 'Auth\AuthController@login')->name('candidate.login');
Route::post('recruiter/login/social', 'Auth\SocialLoginController@index')->name('recruiter.login.social');
Route::post('candidate/login/social', 'Auth\SocialLoginController@index')->name('candidate.login.social');
