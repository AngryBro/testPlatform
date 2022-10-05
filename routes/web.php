<?php

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

Route::get('/', function () {
    //return redirect('/login');
    return view('index');
});
// ->middleware(App\Http\Middleware\NoCors::class);

Route::get('/debug', 'App\Http\Controllers\DebugController@debug')
->middleware(App\Http\Middleware\HasActiveTest::class);
Route::post('/debug', 'App\Http\Controllers\DebugController@debugPost');

Route::view('/debugView','debug');

Route::get('/js/{file}','App\Http\Controllers\AssetsController@asset');
Route::get('/css/{file}','App\Http\Controllers\AssetsController@asset');

Route::view('/login','login');
Route::prefix('admin')
//->middleware(App\Http\Middleware\IsAdmin::class)
->middleware(App\Http\Middleware\NoCors::class)
->group(function () {
    Route::view('/users','admin/users');
    Route::view('','index');
    Route::view('/kims','admin/kims');
    Route::view('/test','admin/tests');
    Route::view('/test/{kim}','test');
    Route::get('/test.download','App\Http\Controllers\AdminController@downloadKimFile');
    
    Route::prefix('api')->group(function () {
        Route::post('/register','App\Http\Controllers\AuthController@register');
        Route::post('/unregister','App\Http\Controllers\AuthController@unregister');
        Route::get('/users','App\Http\Controllers\UserDataController@getEmailsAndKims');
        Route::post('/kims.add','App\Http\Controllers\AdminController@addKim');
        Route::get('/kims.get','App\Http\Controllers\AdminController@getKims');
        Route::post('/kims.delete','App\Http\Controllers\AdminController@delKims');
        Route::get('/test.get','App\Http\Controllers\AdminController@getKimData');
    });
});

// Route::prefix('test')
// ->middleware(App\Http\Middleware\NoCors::class)
// ->group(function () {
//     Route::get('/download','App\Http\Controllers\UserDataController@downloadFile');
//     Route::prefix('api')->group(function () {
//         Route::group(function() {
//             Route::get('/data','App\Http\Controllers\UserDataController@getKimData');
//             Route::get('/img','App\Http\Controllers\UserDataController@getKimTask');
//             Route::post('/save','App\Http\Controllers\UserDataController@saveAnswers');
//             Route::get('/saved','App\Http\Controllers\UserDataController@getSavedAnswers');
//             Route::post('/end','App\Http\Controllers\UserDataController@startExam');
//         })
//         ->middleware(App\Http\Middleware\HasActiveTest::class)
//         ;
//         Route::post('/start','App\Http\Controllers\UserDataController@startExam');
//     });
// });

Route::get('/logout','App\Http\Controllers\AuthController@logout');
Route::post('/api/login','App\Http\Controllers\AuthController@login')->middleware(App\Http\Middleware\NoCors::class);
Route::get('/api/role', function () {
    return response()->json(['role' => session()->has('role')?session('role'):'guest']);
});
// Route::prefix('api')
// ->middleware(App\Http\Middleware\IsUser::class)
// ->group(function () {

// });