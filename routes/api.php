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

        Route::get('/exams', 'ExamController@index')->middleware('can:viewAny,App\Exam');
        Route::post('/exam', 'ExamController@store')->middleware('can:create,App\Exam');
        Route::delete('/exam/{exam}', 'ExamController@destroy')->middleware('can:delete,exam');
        Route::put('/exam/{exam}', 'ExamController@update')->middleware('can:update,exam');
        Route::get('/exam/{exam}/accesses', 'ExamController@accesses')->middleware('can:viewAny,exam');
        Route::get('/exam/{exam}/download', 'ExamController@download')->middleware('can:view,exam');

        Route::get('/users', 'UserController@index')->middleware('can:viewAny,App\User');
        Route::delete('/user/{user}', 'UserController@destroy')->middleware('can:delete,user');
        Route::put('/user/{user}', 'UserController@update')->middleware('can:update,user');
        Route::post('/user', 'UserController@store')->middleware('can:create,App\User');

        Route::post('/access', 'AccessController@store')->middleware('can:create,App\Access');
        Route::put('/access/{access}', 'AccessController@update')->middleware('can:update,access');
        Route::delete('/access/{access}', 'AccessController@destroy')->middleware('can:delete,access');
        Route::post('/access/update_bulk', 'AccessController@update_bulk')->middleware('can:create,App\Access'); //Bulk update requires same level of authority as create
        
        Route::get('/downloads', 'DownloadController@index')->middleware('can:viewAny,App\Download');
        Route::get('/uploads', 'UploadController@index')->middleware('can:viewAny,App\Upload');
        Route::post('/upload', 'UploadController@store')->middleware('can:create,App\Upload');
    });
});