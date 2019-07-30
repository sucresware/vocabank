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

Route::get('/home', 'HomeController@index')->name('home');
Route::view('/terms', 'static/terms')->name('terms');

Route::get('/users', 'UserController@index')->name('users.index');
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

Route::post('/samples/preflight', 'SampleController@preflight')->name('samples.preflight');
Route::post('/samples/preflight/youtube', 'SampleController@preflightYouTube')->name('samples.preflight.youtube');
Route::get('/samples/{sample}/process-youtube', 'SampleController@processYouTube')->name('samples.process.youtube');

Route::resource('samples', 'SampleController');
