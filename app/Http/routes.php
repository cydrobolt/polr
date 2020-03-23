<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/* Optional endpoints */
if (env('POLR_ALLOW_ACCT_CREATION')) {
    Route::get('/signup', 'UserController@displaySignupPage')->name('signup');
    Route::post('/signup', 'UserController@performSignup')->name('psignup');
}


Route::get('/', 'IndexController@showIndexPage')->name('index');
Route::get('/logout', 'UserController@performLogoutUser')->name('logout');
Route::get('/login', 'UserController@displayLoginPage')->name('login');
Route::get('/about-polr', 'StaticPageController@displayAbout')->name('about');
Route::get('/about-bakjp', 'StaticPageController@displayAboutBakjp')->name('about_bakjp');

Route::get('/lost_password', 'UserController@displayLostPasswordPage')->name('lost_password');
Route::get('/activate/{username}/{recovery_key}', 'UserController@performActivation')->name('activate');
Route::get('/reset_password/{username}/{recovery_key}', 'UserController@performPasswordReset')->name('reset_password');
Route::get('/admin', 'AdminController@displayAdminPage')->name('admin');

Route::get('/setup', 'SetupController@displaySetupPage')->name('setup');
Route::post('/setup', 'SetupController@performSetup')->name('psetup');
Route::post('/setup/finish', 'SetupController@finishSetup')->name('setup_finish');


/* GET endpoints */
Route::get('/{short_url}', 'LinkController@performRedirect');
Route::get('/{short_url}/{secret_key}', 'LinkController@performRedirect');
Route::get('/admin/stats/{short_url}', 'StatsController@displayStats');

/* POST endpoints */
Route::post('/login', 'UserController@performLogin')->name('plogin');
Route::post('/shorten', 'LinkController@performShorten')->name('pshorten');
Route::post('/lost_password', 'UserController@performSendPasswordResetCode')->name('plost_password');
Route::post('/reset_password/{username}/{recovery_key}', 'UserController@performPasswordReset')->name('preset_password');
Route::post('/admin/action/change_password', 'AdminController@changePassword')->name('change_password');


Route::group(['prefix' => '/api/v2', 'namespace' => 'App\Http\Controllers'], function () {
    Route::post('link_avail_check', 'AjaxController@checkLinkAvailability')->name('api_link_check');
    Route::post('admin/toggle_api_active', 'AjaxController@toggleAPIActive')->name('api_toggle_api_active');
    Route::post('admin/generate_new_api_key', 'AjaxController@generateNewAPIKey')->name('api_generate_new_api_key');
    Route::post('admin/edit_api_quota', 'AjaxController@editAPIQuota')->name('api_edit_quota');
    Route::post('admin/toggle_user_active', 'AjaxController@toggleUserActive')->name('api_toggle_user_active');
    Route::post('admin/change_user_role', 'AjaxController@changeUserRole')->name('api_change_user_role');
    Route::post('admin/add_new_user', 'AjaxController@addNewUser')->name('api_add_new_user');
    Route::post('admin/delete_user', 'AjaxController@deleteUser')->name('api_delete_user');
    Route::post('admin/toggle_link', 'AjaxController@toggleLink')->name('api_toggle_link');
    Route::post('admin/delete_link', 'AjaxController@deleteLink')->name('api_delete_link');
    Route::post('admin/edit_link_long_url', 'AjaxController@editLinkLongUrl')->name('api_edit_link_long_url');
    Route::get('admin/get_admin_users', 'AdminPaginationController@paginateAdminUsers')->name('api_get_admin_users');
    Route::get('admin/get_admin_links', 'AdminPaginationController@paginateAdminLinks')->name('api_get_admin_links');
    Route::get('admin/get_user_links', 'AdminPaginationController@paginateUserLinks')->name('api_get_user_links');
});

Route::group(['prefix' => '/api/v2', 'namespace' => 'App\Http\Controllers\Api','middleware' => 'api'], function () {
    Route::post('action/shorten', 'ApiLinkController@shortenLink')->name('api_shorten_url');
    Route::get('action/shorten', 'ApiLinkController@shortenLink')->name('api_shorten_url');
    Route::post('action/shorten_bulk', 'ApiLinkController@shortenLinksBulk')->name('api_shorten_url_bulk');

    Route::post('action/lookup', 'ApiLinkController@lookupLink')->name('api_lookup_url');
    Route::get('action/lookup', 'ApiLinkController@lookupLink')->name('api_lookup_url');

    Route::get('data/link', 'ApiAnalyticsController@lookupLinkStats')->name('api_link_analytics');
    Route::post('data/link', 'ApiAnalyticsController@lookupLinkStats')->name('api_link_analytics');

});

