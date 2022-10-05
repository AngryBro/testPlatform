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

// Route::middleware(App\Http\Middleware\NoCors::class)
// ->group(function() {


Route::middleware(App\Http\Middleware\HasActiveTest::class)
->group(function() {
    Route::middleware(App\Http\Middleware\JsonRequest::class)
    ->group(function() {
        Route::post('/test.save','App\Http\Controllers\UserDataController@saveAnswers');
        Route::post('/test.end','App\Http\Controllers\UserDataController@endExam');
    });
    Route::get('/test.data','App\Http\Controllers\UserDataController@getKimData');
    
    Route::get('/test.saved','App\Http\Controllers\UserDataController@getSavedAnswers');
    
    Route::get('/test.download','App\Http\Controllers\UserDataController@downloadFile');
    Route::get('/test.img','App\Http\Controllers\UserDataController@getKimTask');
});
Route::post('/test.start','App\Http\Controllers\UserDataController@startExam');


// });



