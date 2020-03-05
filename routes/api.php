<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
    if(env('APP_DEBUG')) {
        Route::options('/{any?}', function() {})->where('any', '.*');
    }

    Route::get('/gitversion', 'AuthController@gitversion');   
    Route::post('/login', 'AuthController@login')->name('login');

    Route::group(['middleware' => 'auth:api'], function()
    {
        Route::post('/logout', 'AuthController@logout');

        Route::get('/exam/{exam}', 'ExamController@view')->middleware('can:view,exam');
        Route::get('/exams', 'ExamController@index')->middleware('can:viewAny,App\Exam');
        Route::get('/exam_names', 'ExamController@names')->middleware('can:viewAny,App\Exam');

        //Checks if an Exam number already exists in the database. This facility is needed by associates for uploading new exams, 
        //so we are using Exam.create gate for authorization.
        Route::get('/exam_number_exists/{number}', 'ExamController@number_exists')->middleware('can:create,App\Exam'); 

        //Upload Exam (associate)
        Route::post('/exam', 'ExamController@store')->middleware('can:create,App\Exam');

        Route::delete('/exam/{exam}', 'ExamController@destroy')->middleware('can:delete,exam');
        Route::put('/exam/{exam}', 'ExamController@update')->middleware('can:update,exam');
        Route::get('/exam/{exam}/accesses', 'ExamController@accesses')->middleware('can:viewAny,exam');

        //Update exam files (only an associate can do. Must be owner of the exam)
        Route::post('/exam/{exam}/update_files', 'ExamController@update_files')->middleware('can:update,exam');

        //Upload Result
        Route::post('/exam/{exam}/upload_result', 'ExamController@upload_result')->middleware('can:uploadResult,exam');

        Route::post('/hfqreport', 'ExamController@hfqreport')->middleware('can:viewreport,App\Exam');
        Route::post('/hfqreportpdf', 'ExamController@hfqreportpdf')->middleware('can:viewreport,App\Exam');

        Route::get('/users', 'UserController@index')->middleware('can:viewAny,App\User');
        Route::delete('/user/{user}', 'UserController@destroy')->middleware('can:delete,user');
        Route::put('/user/{user}', 'UserController@update')->middleware('can:update,user');
        Route::post('/user', 'UserController@store')->middleware('can:create,App\User');

        //Exams that current user can download
        Route::get('/user/myexams/dl', 'UserController@myexamsdl');

        //Exams owned by current user (associate)
        Route::get('/user/myexams/ul', 'UserController@myexamsul');

        Route::post('/access', 'AccessController@store')->middleware('can:create,App\Access');
        Route::put('/access/{access}', 'AccessController@update')->middleware('can:update,access');
        Route::delete('/access/{access}', 'AccessController@destroy')->middleware('can:delete,access');
        Route::post('/access/update_bulk', 'AccessController@update_bulk')->middleware('can:create,App\Access'); //Bulk update requires same level of authority as create

        //Download exam
        Route::post('/access/{access}/download', 'AccessController@download')->middleware('can:download,access');
        
        Route::get('/downloads', 'DownloadController@index')->middleware('can:viewAny,App\Download');
        Route::get('/uploads', 'UploadController@index')->middleware('can:viewAny,App\Upload');
        Route::get('/upload/locations', 'UploadController@locations')->middleware('can:viewAny,App\Upload');
        Route::get('/upload/dates/{exam}', 'UploadController@dates')->middleware('can:viewAny,App\Upload');

        //Camera snapshot upload
        Route::post('/download/{download}/snapshot', 'DownloadController@snapshot')->middleware('can:uploadSnapshot,download');

        //Camera snapshot upload
        Route::get('/download/{download}/snapshots', 'DownloadController@snapshots')->middleware('can:viewSnapshots,download');
    });
});