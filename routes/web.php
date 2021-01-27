<?php

/**
 * Authentication
 */
Route::get('login', 'Auth\LoginController@show');
Route::post('login', 'Auth\LoginController@login')->name('admin.login');
Route::get('logout', 'Auth\LoginController@logout')->name('auth.logout');

Route::group(['middleware' => ['registration', 'guest']], function () {
    Route::get('register', 'Auth\RegisterController@show');
    Route::post('register', 'Auth\RegisterController@register');
});

Route::emailVerification();

Route::group(['middleware' => ['password-reset', 'guest']], function () {
    Route::resetPassword();
});

/**
 * Two-Factor Authentication
 */
Route::group(['middleware' => 'two-factor'], function () {
    Route::get('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@show')->name('auth.token');
    Route::post('auth/two-factor-authentication', 'Auth\TwoFactorTokenController@update')->name('auth.token.validate');
});

/**
 * Social Login
 */
Route::get('auth/{provider}/login', 'Auth\SocialAuthController@redirectToProvider')->name('social.login');
Route::get('auth/{provider}/callback', 'Auth\SocialAuthController@handleProviderCallback');

Route::group(['middleware' => ['auth', 'verified']], function () {

    /**
     * Impersonate Routes
     */
    Route::impersonate();

    /**
     * Dashboard
     */

    Route::get('/', 'DashboardController@index')->name('dashboard');

    /**
     * User Profile
     */

    Route::group(['prefix' => 'profile', 'namespace' => 'Profile'], function () {
        Route::get('/', 'ProfileController@show')->name('profile');
        Route::get('activity', 'ActivityController@show')->name('profile.activity');
        Route::put('details', 'DetailsController@update')->name('profile.update.details');

        Route::post('avatar', 'AvatarController@update')->name('profile.update.avatar');
        Route::post('avatar/external', 'AvatarController@updateExternal')
            ->name('profile.update.avatar-external');

        Route::put('login-details', 'LoginDetailsController@update')
            ->name('profile.update.login-details');

        Route::get('sessions', 'SessionsController@index')
            ->name('profile.sessions')
            ->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'SessionsController@destroy')
            ->name('profile.sessions.invalidate')
            ->middleware('session.database');
    });

    /**
     * Two-Factor Authentication Setup
     */

    Route::group(['middleware' => 'two-factor'], function () {
        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('two-factor.enable');

        Route::get('two-factor/verification', 'TwoFactorController@verification')
            ->name('two-factor.verification')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/resend', 'TwoFactorController@resend')
            ->name('two-factor.resend')
            ->middleware('throttle:1,1', 'verify-2fa-phone');

        Route::post('two-factor/verify', 'TwoFactorController@verify')
            ->name('two-factor.verify')
            ->middleware('verify-2fa-phone');

        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('two-factor.disable');
    });



    /**
     * User Management
     */
    Route::resource('users', 'Users\UsersController')
        ->except('update')->middleware('permission:users.manage');

    Route::group(['prefix' => 'users/{user}', 'middleware' => 'permission:users.manage'], function () {
        Route::put('update/details', 'Users\DetailsController@update')->name('users.update.details');
        Route::put('update/login-details', 'Users\LoginDetailsController@update')
            ->name('users.update.login-details');

        Route::post('update/avatar', 'Users\AvatarController@update')->name('user.update.avatar');
        Route::post('update/avatar/external', 'Users\AvatarController@updateExternal')
            ->name('user.update.avatar.external');

        Route::get('sessions', 'Users\SessionsController@index')
            ->name('user.sessions')->middleware('session.database');

        Route::delete('sessions/{session}/invalidate', 'Users\SessionsController@destroy')
            ->name('user.sessions.invalidate')->middleware('session.database');

        Route::post('two-factor/enable', 'TwoFactorController@enable')->name('user.two-factor.enable');
        Route::post('two-factor/disable', 'TwoFactorController@disable')->name('user.two-factor.disable');
    });
    
    /**
     * Roles & Permissions
     */
    Route::group(['namespace' => 'Authorization'], function () {
        Route::resource('roles', 'RolesController')->except('show')->middleware('permission:roles.manage');

        Route::post('permissions/save', 'RolePermissionsController@update')
            ->name('permissions.save')
            ->middleware('permission:permissions.manage');

        Route::resource('permissions', 'PermissionsController')->middleware('permission:permissions.manage');
    });
    
    /**
     * Sale stages
     */
    Route::get('sale-stages', 'SaleStages\SaleStagesController@index')->middleware('permission:sale-stages.index')->name('sale-stages.index');
    Route::get('sale-stages/create', 'SaleStages\SaleStagesController@create')->middleware('permission:sale-stages.create')->name('sale-stages.create');
    Route::post('sale-stages/store', 'SaleStages\SaleStagesController@store')->middleware('permission:sale-stages.create')->name('sale-stages.store');
    Route::get('sale-stages/{saleStage}/edit', 'SaleStages\SaleStagesController@edit')->middleware('permission:sale-stages.update')->name('sale-stages.edit');
    Route::put('sale-stages/{saleStage}/update', 'SaleStages\SaleStagesController@update')->middleware('permission:sale-stages.update')->name('sale-stages.update');
    Route::delete('sale-stages/{saleStage}/destroy', 'SaleStages\SaleStagesController@destroy')->middleware('permission:sale-stages.delete')->name('sale-stages.destroy');
    
    
    /**
     * Campaigns
     */
    Route::get('campaigns', 'Campaigns\CampaignsController@index')->middleware('permission:campaigns.index')->name('campaigns.index');
    Route::get('campaigns/create', 'Campaigns\CampaignsController@create')->middleware('permission:campaigns.create')->name('campaigns.create');
    Route::post('campaigns/store', 'Campaigns\CampaignsController@store')->middleware('permission:campaigns.create')->name('campaigns.store');
    Route::get('campaigns/{campaign}/edit', 'Campaigns\CampaignsController@edit')->middleware('permission:campaigns.update')->name('campaigns.edit');
    Route::put('campaigns/{campaign}/update', 'Campaigns\CampaignsController@update')->middleware('permission:campaigns.update')->name('campaigns.update');
    Route::delete('campaigns/{campaign}/destroy', 'Campaigns\CampaignsController@destroy')->middleware('permission:campaigns.delete')->name('campaigns.destroy');
    /**
     * Clients
     */
    Route::get('clients/create', 'Clients\ClientsController@create')->middleware('permission:clients.create')->name('clients.create');
    Route::get('clients', 'Clients\ClientsController@index')->middleware('permission:clients.index')->name('clients.index');
    Route::get('clients/import', 'Clients\ClientsController@import')->middleware('permission:clients.import')->name('clients.import');
    Route::post('clients/assign-client', 'Clients\ClientsController@assignClient')->middleware('permission:clients.assign-client')->name('clients.assign-client');
    Route::post('clients/delete-bulk', 'Clients\ClientsController@deleteBulk')->middleware('permission:clients.delete')->name('clients.delete-bulk');
    Route::post('clients/export-client', 'Clients\ClientsController@exportClient')->middleware('permission:clients.export')->name('clients.export-client');
    Route::post('clients/make-import-clients', 'Clients\ClientsController@makeImportClient')->middleware('permission:clients.import')->name('clients.make-import-clients');
   
    
    Route::post('clients/store', 'Clients\ClientsController@store')->middleware('permission:clients.create')->name('clients.store');
    Route::get('clients/{client}/edit', 'Clients\ClientsController@edit')->middleware('permission:clients.update')->name('clients.edit');
    Route::put('clients/{client}/update', 'Clients\ClientsController@update')->middleware('permission:clients.update')->name('clients.update');
    Route::delete('clients/{client}/destroy', 'Clients\ClientsController@destroy')->middleware('permission:clients.delete')->name('clients.destroy');
    /**
     * Settings
     */

    Route::get('settings', 'SettingsController@general')->name('settings.general')
        ->middleware('permission:settings.general');

    Route::post('settings/general', 'SettingsController@update')->name('settings.general.update')
        ->middleware('permission:settings.general');

    Route::get('settings/auth', 'SettingsController@auth')->name('settings.auth')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth', 'SettingsController@update')->name('settings.auth.update')
        ->middleware('permission:settings.auth');

    if (config('services.authy.key')) {
        Route::post('settings/auth/2fa/enable', 'SettingsController@enableTwoFactor')
            ->name('settings.auth.2fa.enable')
            ->middleware('permission:settings.auth');

        Route::post('settings/auth/2fa/disable', 'SettingsController@disableTwoFactor')
            ->name('settings.auth.2fa.disable')
            ->middleware('permission:settings.auth');
    }

    Route::post('settings/auth/registration/captcha/enable', 'SettingsController@enableCaptcha')
        ->name('settings.registration.captcha.enable')
        ->middleware('permission:settings.auth');

    Route::post('settings/auth/registration/captcha/disable', 'SettingsController@disableCaptcha')
        ->name('settings.registration.captcha.disable')
        ->middleware('permission:settings.auth');

    Route::get('settings/notifications', 'SettingsController@notifications')
        ->name('settings.notifications')
        ->middleware('permission:settings.notifications');

    Route::post('settings/notifications', 'SettingsController@update')
        ->name('settings.notifications.update')
        ->middleware('permission:settings.notifications');

    /**
     * Activity Log
     */
    Route::get('activity', 'Activity\ActivityController@index')->name('activity.index')
        ->middleware('permission:users.activity');

    Route::get('activity/user/{user}/log', 'Activity\UserActivityController@index')->name('activity.user')
        ->middleware('permission:users.activity');

    Route::get('profile/activity', 'Activity\UserActivityController@show')->name('profile.activity');

    Route::get('announcements/list', 'Announcement\AnnouncementListController@index')
        ->name('announcements.list');

    Route::post('announcements/read', 'Announcement\ReadAnnouncementsController@index');

    Route::resource('announcements', 'Announcement\AnnouncementsController');

});


/**
 * Installation
 */

Route::group(['prefix' => 'install'], function () {
    Route::get('/', 'InstallController@index')->name('install.start');
    Route::get('requirements', 'InstallController@requirements')->name('install.requirements');
    Route::get('permissions', 'InstallController@permissions')->name('install.permissions');
    Route::get('database', 'InstallController@databaseInfo')->name('install.database');
    Route::get('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('start-installation', 'InstallController@installation')->name('install.installation');
    Route::post('install-app', 'InstallController@install')->name('install.install');
    Route::get('complete', 'InstallController@complete')->name('install.complete');
    Route::get('error', 'InstallController@error')->name('install.error');
});
