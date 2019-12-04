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
});

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});