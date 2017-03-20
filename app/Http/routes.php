<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
*/

/* Optional endpoints */
if (env('POLR_ALLOW_ACCT_CREATION')) {
    $app->get('/signup', ['as' => 'signup', 'uses' => 'UserController@displaySignupPage']);
    $app->post('/signup', ['as' => 'psignup', 'uses' => 'UserController@performSignup']);
}

/* GET endpoints */

$app->get('/', ['as' => 'index', 'uses' => 'IndexController@showIndexPage']);
$app->get('/logout', ['as' => 'logout', 'uses' => 'UserController@performLogoutUser']);
$app->get('/login', ['as' => 'login', 'uses' => 'UserController@displayLoginPage']);
$app->get('/about-polr', ['as' => 'about', 'uses' => 'StaticPageController@displayAbout']);

$app->get('/lost_password', ['as' => 'lost_password', 'uses' => 'UserController@displayLostPasswordPage']);
$app->get('/activate/{username}/{recovery_key}', ['as' => 'activate', 'uses' => 'UserController@performActivation']);
$app->get('/reset_password/{username}/{recovery_key}', ['as' => 'reset_password', 'uses' => 'UserController@performPasswordReset']);

$app->get('/admin', ['as' => 'admin', 'uses' => 'AdminController@displayAdminPage']);

$app->get('/setup', ['as' => 'setup', 'uses' => 'SetupController@displaySetupPage']);
$app->post('/setup', ['as' => 'psetup', 'uses' => 'SetupController@performSetup']);
$app->get('/setup/finish', ['as' => 'setup_finish', 'uses' => 'SetupController@finishSetup']);

$app->get('/{short_url}', ['uses' => 'LinkController@performRedirect']);
$app->get('/{short_url}/{secret_key}', ['uses' => 'LinkController@performRedirect']);

$app->get('/admin/stats/{short_url}', ['uses' => 'StatsController@displayStats']);

/* POST endpoints */

$app->post('/login', ['as' => 'plogin', 'uses' => 'UserController@performLogin']);
$app->post('/shorten', ['as' => 'pshorten', 'uses' => 'LinkController@performShorten']);
$app->post('/lost_password', ['as' => 'plost_password', 'uses' => 'UserController@performSendPasswordResetCode']);
$app->post('/reset_password/{username}/{recovery_key}', ['as' => 'preset_password', 'uses' => 'UserController@performPasswordReset']);

$app->post('/admin/action/change_password', ['as' => 'change_password', 'uses' => 'AdminController@changePassword']);

$app->group(['prefix' => '/api/v2', 'namespace' => 'App\Http\Controllers'], function ($app) {
    /* API internal endpoints */
    $app->post('link_avail_check', ['as' => 'api_link_check', 'uses' => 'AjaxController@checkLinkAvailability']);
    $app->post('admin/toggle_api_active', ['as' => 'api_toggle_api_active', 'uses' => 'AjaxController@toggleAPIActive']);
    $app->post('admin/generate_new_api_key', ['as' => 'api_generate_new_api_key', 'uses' => 'AjaxController@generateNewAPIKey']);
    $app->post('admin/edit_api_quota', ['as' => 'api_edit_quota', 'uses' => 'AjaxController@editAPIQuota']);
    $app->post('admin/toggle_user_active', ['as' => 'api_toggle_user_active', 'uses' => 'AjaxController@toggleUserActive']);
    $app->post('admin/change_user_role', ['as' => 'api_change_user_role', 'uses' => 'AjaxController@changeUserRole']);
    $app->post('admin/add_new_user', ['as' => 'api_add_new_user', 'uses' => 'AjaxController@addNewUser']);
    $app->post('admin/delete_user', ['as' => 'api_delete_user', 'uses' => 'AjaxController@deleteUser']);
    $app->post('admin/toggle_link', ['as' => 'api_toggle_link', 'uses' => 'AjaxController@toggleLink']);
    $app->post('admin/delete_link', ['as' => 'api_delete_link', 'uses' => 'AjaxController@deleteLink']);

    $app->get('admin/get_admin_users', ['as' => 'api_get_admin_users', 'uses' => 'AdminPaginationController@paginateAdminUsers']);
    $app->get('admin/get_admin_links', ['as' => 'api_get_admin_links', 'uses' => 'AdminPaginationController@paginateAdminLinks']);
    $app->get('admin/get_user_links', ['as' => 'api_get_user_links', 'uses' => 'AdminPaginationController@paginateUserLinks']);
});

$app->group(['prefix' => '/api/v2', 'namespace' => 'App\Http\Controllers\Api', 'middleware' => 'api'], function ($app) {
    /* API shorten endpoints */
    $app->post('action/shorten', ['as' => 'api_shorten_url', 'uses' => 'ApiLinkController@shortenLink']);
    $app->get('action/shorten', ['as' => 'api_shorten_url', 'uses' => 'ApiLinkController@shortenLink']);

    /* API lookup endpoints */
    $app->post('action/lookup', ['as' => 'api_lookup_url', 'uses' => 'ApiLinkController@lookupLink']);
    $app->get('action/lookup', ['as' => 'api_lookup_url', 'uses' => 'ApiLinkController@lookupLink']);

    /* API data endpoints */
    $app->get('data/link', ['as' => 'api_link_analytics', 'uses' => 'ApiAnalyticsController@lookupLinkStats']);
    $app->post('data/link', ['as' => 'api_link_analytics', 'uses' => 'ApiAnalyticsController@lookupLinkStats']);
});
