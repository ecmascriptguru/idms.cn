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
        Route::get('/roles/full-list', 'RolesController@fullList');
         
        Route::resource('/roles', 'RolesController', [
            'except' => ['create', 'edit'],
        ]);


        Route::get('/ops/full-list', 'OperatingCompaniesController@fullList');
        
        Route::resource('/ops', 'OperatingCompaniesController', [
           'except' => ['create', 'edit'],
        ]);
    });
});
