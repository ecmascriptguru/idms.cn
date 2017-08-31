<?php

Route::group([
    'middleware' => ['cors', 'api'],
], function () {
    Route::post('/auth/token/issue', 'AuthController@issueToken');
    Route::post('/auth/token/refresh', 'AuthController@refreshToken');

    Route::group([
        'middleware' => 'jwt.auth',
    ], function () {
        Route::post('/auth/token/revoke', 'AuthController@revokeToken');
        Route::get('/categories/full-list', 'CategoriesController@fullList');

        Route::resource('/categories', 'CategoriesController', [
            'except' => ['create', 'edit'],
        ]);

        Route::resource('/products', 'ProductsController', [
            'except' => ['create', 'edit'],
        ]);

        Route::get('/me', 'MeController@show');


        /**
         *  Created by Alex
         */
        Route::get('/roles/full-list', 'Admin\RolesController@fullList');         
        Route::resource('/roles', 'Admin\RolesController', [
            'except' => ['create', 'edit'],
        ]);


        Route::get('/ops/full-list', 'Admin\OperatingCompaniesController@fullList');        
        Route::resource('/ops', 'Admin\OperatingCompaniesController', [
           'except' => ['create', 'edit'],
        ]);

        Route::get('/users/full-list', 'Admin\UsersController@fullList');        
        Route::resource('/users', 'Admin\UsersController', [
           'except' => ['create', 'edit'],
        ]);
    });
});
