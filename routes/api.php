<?php

Route::group(['prefix' => 'v1'], function () {
    Route::get('/samples', 'Api\SampleController@index');
    Route::get('/samples/search', 'Api\SampleController@search');
    Route::get('/samples/{sample}', 'Api\SampleController@show');
    Route::get('/users', 'Api\UserController@index');
    Route::get('/users/{user}', 'Api\UserController@show');
});
