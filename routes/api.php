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
    Route::post('/login', 'AuthController@login')->middleware('throttle:15,5');
    Route::post('/register', 'AuthController@register')->middleware('throttle:112,10');    

    Route::group(['middleware' => 'auth:api'], function()
    {
        Route::post('/logout', 'AuthController@logout');

        Route::get('/exams', 'ExamController@index');
        Route::delete('/exam/{exam}', 'ExamController@destroy');
        Route::put('/exam/{exam}', 'ExamController@update');

        Route::get('/users', 'UserController@index');
        Route::delete('/user/{user}', 'UserController@destroy');
        Route::put('/user/{user}', 'UserController@update');
        Route::post('/user', 'UserController@store');
    });
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});