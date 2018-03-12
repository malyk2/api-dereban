<?php
Route::prefix('v1')->group(function() {
    //public routes
    Route::post('login', 'AuthController@login');
    Route::post('register', 'AuthController@register');
    Route::post('registerActivate', 'AuthController@registerActivate');
    Route::post('activate', 'AuthController@activate');
    Route::post('forgotPassword', 'AuthController@forgotPassword');
    Route::post('changePassword', 'AuthController@changePassword');

    Route::prefix('user')->group(function() {
        //private routes
        Route::middleware('auth:api')->group(function(){
            Route::post('changeLang', 'UserController@changeLang');
            Route::get('getAuthUserInfo', 'UserController@getAuthUserInfo');
            Route::post('checkExistsByEmail', 'UserController@checkExistsByEmail');
        });
    });
    Route::prefix('group')->group(function() {
        //public routes
        //private routes
        Route::middleware('auth:api')->group(function(){
            Route::post('create', 'GroupController@create');
            Route::put('update/{group}', 'GroupController@update');
            Route::delete('delete/{group}', 'GroupController@delete');
            Route::get('getAllUsersGroups', 'GroupController@getAllUsersGroups');
            Route::get('getGroupUsers/{group}', 'GroupController@getGroupUsers');
            Route::post('{group}/addUserToGroup', 'GroupController@addUserToGroup');
            Route::delete('{group}/removeUser/{user}', 'GroupController@removeUser');
        });
    });
});
