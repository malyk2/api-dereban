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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

//Route::auth();
Route::prefix('v1')->group(function(){
    Route::post('/user/create', 'UserController@create');
    //Route::post('/user/login', 'UserController@login');
});
Route::prefix('v1')->middleware('auth:api')->group(function(){
    Route::get('/user/test', 'UserController@test');
});

//Route::any('login', ['as' => 'login',
//                    'use'=>'UserController@test']);


//Route::get('test', 'UserController@test');
//
//Route::post('create', 'UserController@create');