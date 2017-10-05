<?php

Route::group([
    'middleware' => ['cors', 'api'],
], function () {
    Route::post('/auth/token/issue', 'AuthController@issueToken');
    Route::post('/auth/token/refresh', 'AuthController@refreshToken');

    Route::group([
        // 'middleware' => 'jwt.auth',
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
         *
         */
        Route::get('files', 'FileEntryController@index');
        Route::get('files/get/{filename}', [
            'as' => 'getentry', 'uses' => 'FileEntryController@get']);
        Route::post('files/add',[ 
                'as' => 'addentry', 'uses' => 'FileEntryController@add']);


        /**
         *  Created by Alex
         *  For administrator
         */
        Route::get('/admin/roles/full-list', 'Admin\RolesController@fullList');         
        Route::resource('/admin/roles', 'Admin\RolesController', [
            'except' => ['create', 'edit'],
        ]);

        Route::get('/admin/houseTypes/full-list', 'Admin\HouseTypesController@fullList');         
        Route::resource('/admin/houseTypes', 'Admin\HouseTypesController', [
            'except' => ['create', 'edit'],
        ]);

        Route::get('/admin/customFeeTypes/full-list', 'Admin\CustomFeeTypesController@fullList');         
        Route::resource('/admin/customFeeTypes', 'Admin\CustomFeeTypesController', [
            'except' => ['create', 'edit'],
        ]);


        Route::get('/admin/ops/full-list', 'Admin\OperatingCompaniesController@fullList');        
        Route::resource('/admin/ops', 'Admin\OperatingCompaniesController', [
           'except' => ['create', 'edit'],
        ]);

        Route::get('/admin/users/full-list', 'Admin\UsersController@fullList');        
        Route::resource('/admin/users', 'Admin\UsersController', [
           'except' => ['create', 'edit'],
        ]);

        /**
         *  For Operating Company Admin
         */
        Route::get('/oca/ppcs/full-list', 'Op\PropertyCompaniesController@fullList');        
        Route::resource('/oca/ppcs', 'Op\PropertyCompaniesController', [
           'except' => ['create', 'edit'],
        ]);

        Route::resource('/oca/shop', 'Op\ShopController', [
           'except' => ['index', 'store', 'destroy', 'create', 'edit'],
        ]);

        Route::get('/oca/users/full-list', 'Op\UsersController@fullList');        
        Route::resource('/oca/users', 'Op\UsersController', [
           'except' => ['create', 'edit'],
        ]);

        Route::get('/oca/app-advs/full-list', 'Op\AppAdvsController@fullList');        
        Route::resource('/oca/app-advs', 'Op\AppAdvsController', [
           'except' => ['create', 'edit'],
        ]);

        Route::get('/oca/device-advs/full-list', 'Op\DeviceAdvsController@fullList');        
        Route::resource('/oca/device-advs', 'Op\DeviceAdvsController', [
           'except' => ['create', 'edit'],
        ]);

        Route::resource('/oca/parameter', 'Op\ParametersController', [
            'except' => ['index', 'store', 'destroy', 'create', 'edit'],
        ]);

        /**
         *  For Property Company admin
         */
        Route::get('/pca/districts/full-list', 'Ppc\DistrictsController@fullList');
        Route::resource('/pca/districts', 'Ppc\DistrictsController', [
           'except' => ['create', 'edit'],
        ]);

        Route::get('/pca/users/full-list', 'Ppc\UsersController@fullList');        
        Route::resource('/pca/users', 'Ppc\UsersController', [
           'except' => ['create', 'edit'],
        ]);
    });
});
