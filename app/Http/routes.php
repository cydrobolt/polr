<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


/* GET endpoints */

$app->get('/', ['as' => 'index', 'uses' => 'IndexController@showIndexPage']);
$app->get('/logout', ['as' => 'logout', 'uses' => 'UserController@logoutUser']);
$app->get('/login', ['as' => 'login', 'uses' => 'UserController@displayLoginPage']);
$app->get('/about', ['as' => 'about', 'uses' => 'StaticPageController@displayAbout']);
$app->get('/signup', ['as' => 'signup', 'uses' => 'UserController@displaySignupPage']);
$app->get('/admin', ['as' => 'admin', 'uses' => 'AdminController@displayAdminPage']);

$app->get('/{short_url}', ['uses' => 'LinkController@performRedirect']);
$app->get('/{short_url}/{secret_key}', ['uses' => 'LinkController@performRedirect']);


/* POST endpoints */

$app->post('/login', ['as' => 'plogin', 'uses' => 'UserController@performLogin']);
$app->post('/shorten', ['as' => 'shorten', 'uses' => 'LinkController@performShorten']);
