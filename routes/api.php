<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('v1')->middleware('cors')->group(function()
{
    Route::post('/login', 'AuthController@login')->middleware('throttle:15,5')->name('login');
    Route::post('/register', 'AuthController@register')->middleware('throttle:112,10')->name('register');    

    Route::group(['middleware' => 'auth:api'], function()
    {
        Route::post('/logout', 'AuthController@logout');

        Route::get('/exams', 'ExamController@index');
        Route::post('/exam', 'ExamController@store');
        Route::delete('/exam/{exam}', 'ExamController@destroy');
        Route::put('/exam/{exam}', 'ExamController@update');
        Route::get('/exam/{exam}/accesses', 'ExamController@accesses');
        Route::get('/exam/{exam}/download', 'ExamController@download');

        Route::get('/users', 'UserController@index');
        Route::delete('/user/{user}', 'UserController@destroy');
        Route::put('/user/{user}', 'UserController@update');
        Route::post('/user', 'UserController@store');

        Route::get('/downloads', 'DownloadController@index');
        Route::get('/uploads', 'UploadController@index');

        Route::post('/access', 'AccessController@store');
        Route::put('/access/{access}', 'AccessController@update');
        Route::delete('/access/{access}', 'AccessController@destroy');
        Route::post('/access/update_bulk', 'AccessController@update_bulk');
        
        Route::post('/upload', 'UploadController@store');

        
    });
});