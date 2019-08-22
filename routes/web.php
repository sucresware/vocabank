<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'HomeController@index');

Route::get('/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('/register', 'Auth\RegisterController@register');

Route::get('/login/4sucres', 'Auth\LoginController@loginWithFourSucres');
Route::get('/login/4sucres/callback', 'Auth\LoginController@loginWithFourSucresCallback');

Route::get('/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::post('/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::get('/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('/password/reset/{token}', 'Auth\ResetPasswordController@reset')->name('password.update');

Route::get('/login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('/login', 'Auth\LoginController@login');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/pages/{slug}', 'StaticPageController@show')->name('pages');

Route::get('/users/{user}', 'UserController@show')->name('users.show');

Route::get('/samples/recent', 'SampleController@recent')->name('samples.recent');
Route::get('/samples/popular', 'SampleController@popular')->name('samples.popular');
Route::get('/samples/search', 'SampleController@search')->name('samples.search');
Route::get('/samples/random', 'SampleController@random')->name('samples.random');

Route::get('/samples/{sample}/listen', 'SampleController@listen')->name('samples.listen');
Route::get('/samples/{sample}/download', 'SampleController@download')->name('samples.download');
Route::get('/samples/{sample}/iframe', 'SampleController@iframe')->name('samples.iframe');
Route::get('/samples/{sample}/next', 'SampleController@next')->name('samples.next');
Route::get('/samples/{sample}/prev', 'SampleController@prev')->name('samples.prev');

Route::resource('samples', 'SampleController');

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
    Route::put('/users/{user}', 'UserController@update')->name('users.update');
    Route::get('/users/{user}/edit/email', 'UserController@editEmail')->name('users.edit.email');
    Route::put('/users/{user}/email', 'UserController@updateEmail')->name('users.update.email');
    Route::get('/users/{user}/edit/password', 'UserController@editPassword')->name('users.edit.password');
    Route::put('/users/{user}/password', 'UserController@updatePassword')->name('users.update.password');

    Route::get('/samples/{sample}/edit', 'SampleController@edit')->name('samples.edit');
    Route::post('/samples/preflight', 'SampleController@preflight')->name('samples.preflight');
    Route::post('/samples/preflight/url', 'SampleController@preflightURL')->name('samples.preflight.url');
    Route::get('/samples/create/url', 'SampleController@createURL')->name('samples.create.url');
    Route::get('/samples/{sample}/process-url', 'SampleController@processURL')->name('samples.process.url');

    Route::get('/light-toggler', 'HomeController@lightToggler');
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    Route::get('/', 'Admin\AdminController@index')->name('index');
    Route::resource('/static-pages', 'Admin\StaticPageController');
});