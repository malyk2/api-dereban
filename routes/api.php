<?php

Route::middleware('apilocale')->group(function (){

    //public routes
    Route::prefix('v1')->group(function(){
        Route::post('/user/register', 'UserController@register');
        Route::post('/user/registerActivate', 'UserController@registerActivate');
        Route::post('/user/login', 'UserController@login');
        Route::post('/user/activate', 'UserController@activate');
        Route::post('/user/forgotPassword', 'UserController@forgotPassword');
        Route::post('/user/changePassword', 'UserController@changePassword');
    });
    
    //private routes
    Route::prefix('v1')->middleware('auth:api')->group(function(){
        Route::post('/user/changeLang', 'UserController@changeLang');
    });
    
});
