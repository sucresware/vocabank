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

Auth::routes();

Route::get('/login/4sucres', 'Auth\LoginController@loginWithFourSucres');
Route::get('/login/4sucres/callback', 'Auth\LoginController@loginWithFourSucresCallback');

Route::get('/home', 'HomeController@index')->name('home');
Route::view('/terms', 'static/terms')->name('terms');
Route::view('/api', 'static/api')->name('api');
Route::view('/themetest', 'static/test')->name('test');

Route::get('/users', 'UserController@index')->name('users.index');
Route::get('/users/{user}', 'UserController@show')->name('users.show');
Route::get('/users/{user}/edit', 'UserController@edit')->name('users.edit');
Route::put('/users/{user}', 'UserController@update')->name('users.update');

Route::get('/users/{user}/edit/email', 'UserController@editEmail')->name('users.edit.email');
Route::put('/users/{user}/email', 'UserController@updateEmail')->name('users.update.email');
Route::get('/users/{user}/edit/password', 'UserController@editPassword')->name('users.edit.password');
Route::put('/users/{user}/password', 'UserController@updatePassword')->name('users.update.password');

Route::get('/samples/recent', 'SampleController@recent')->name('samples.recent');
Route::get('/samples/popular', 'SampleController@popular')->name('samples.popular');
Route::get('/samples/search', 'SampleController@search')->name('samples.search');
Route::get('/samples/random', 'SampleController@random')->name('samples.random');

Route::get('/samples/{sample}/listen', 'SampleController@listen')->name('samples.listen');
Route::get('/samples/{sample}/download', 'SampleController@download')->name('samples.download');
Route::get('/samples/{sample}/iframe', 'SampleController@iframe')->name('samples.iframe');

Route::get('/samples/{sample}/next', 'SampleController@next')->name('samples.next');
Route::get('/samples/{sample}/prev', 'SampleController@prev')->name('samples.prev');

Route::get('/samples/{sample}/edit', 'SampleController@edit')->name('samples.edit');

Route::post('/samples/preflight', 'SampleController@preflight')->name('samples.preflight');
Route::post('/samples/preflight/youtube', 'SampleController@preflightYouTube')->name('samples.preflight.youtube');
Route::get('/samples/{sample}/process-youtube', 'SampleController@processYouTube')->name('samples.process.youtube');

Route::resource('samples', 'SampleController');

Route::group(['middleware' => 'auth'], function () {
    Route::get('/light-toggler', function () {
        switch (auth()->user()->getSetting('layout.theme', 'theme-vocabank')) {
            case 'theme-vocabank':
                auth()->user()->setSetting('layout.theme', 'theme-legacy');

            break;
            case 'theme-legacy':
                auth()->user()->setSetting('layout.theme', 'theme-vocabank');

            break;
        }

        return ['success' => true];
    });
});
