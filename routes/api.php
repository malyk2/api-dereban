<?php
Route::prefix('v1')->group(function() {
    Route::prefix('user')->group(function() {
        //public routes
        Route::post('register', 'UserController@register');
        Route::post('registerActivate', 'UserController@registerActivate');
        Route::post('login', 'UserController@login');
        Route::post('activate', 'UserController@activate');
        Route::post('forgotPassword', 'UserController@forgotPassword');
        Route::post('changePassword', 'UserController@changePassword');
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
