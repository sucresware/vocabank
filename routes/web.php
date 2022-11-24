<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\StaticPageController as AdminStaticPageController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SampleController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [HomeController::class, 'index']);

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset/{token}', [ResetPasswordController::class, 'reset'])->name('password.update');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/pages/{slug}', [StaticPageController::class, 'show'])->name('pages');

Route::get('/users/{user}', [UserController::class, 'show'])->name('users.show');

Route::permanentRedirect('/samples/recent', '/samples?order=recent');
Route::permanentRedirect('/samples/popular', '/samples?order=popular');
Route::get('/samples/random', [SampleController::class, 'random'])->name('samples.random');

Route::get('/samples/search', [SampleController::class, 'search'])->name('samples.search');
Route::get('/samples/{sample}/data', [SampleController::class, 'data'])->name('samples.data');
Route::get('/samples/{sample}/listen', [SampleController::class, 'listen'])->name('samples.listen');
Route::get('/samples/{sample}/download{extension?}', [SampleController::class, 'download'])->name('samples.download');
Route::get('/samples/{sample}/iframe', [SampleController::class, 'iframe'])->name('samples.iframe');

Route::get('/samples/{sample}/next', [SampleController::class, 'next'])->name('samples.next');
Route::get('/samples/{sample}/prev', [SampleController::class, 'prev'])->name('samples.prev');

Route::resource('samples', SampleController::class);

Route::group(['middleware' => 'auth'], function () {
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::get('/users/{user}/edit/email', [UserController::class, 'editEmail'])->name('users.edit.email');
    Route::put('/users/{user}/email', [UserController::class, 'updateEmail'])->name('users.update.email');
    Route::get('/users/{user}/edit/password', [UserController::class, 'editPassword'])->name('users.edit.password');
    Route::put('/users/{user}/password', [UserController::class, 'updatePassword'])->name('users.update.password');
    Route::get('/users/{user}/delete', [UserController::class, 'delete'])->name('users.delete');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::post('/samples/{sample}/like', [SampleController::class, 'like'])->name('samples.like');
    Route::get('/samples/{sample}/edit', [SampleController::class, 'edit'])->name('samples.edit');
    Route::post('/samples/preflight', [SampleController::class, 'preflight'])->name('samples.preflight');
    Route::post('/samples/preflight/url', [SampleController::class, 'preflightURL'])->name('samples.preflight.url');
    Route::get('/samples/create/url', [SampleController::class, 'createURL'])->name('samples.create.url');

    Route::get('/light-toggler', [HomeController::class, 'lightToggler']);
});

Route::group(['prefix' => 'admin', 'middleware' => ['auth', 'role:admin'], 'as' => 'admin.'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    Route::resource('/static-pages', AdminStaticPageController::class);
});
