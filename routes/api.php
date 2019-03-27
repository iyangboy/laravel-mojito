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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::namespace('\App\Http\Controllers')
    ->middleware([config('mojito.super_admin.auth'), 'mojito.permission'])
    ->group(function ($router) {
        $router->resource('account_management', 'AccountController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);
        $router->get('account_subset/password_validate', 'AccountSubsetController@passwordValidate');
        $router->get('account_subset/{company_id}/company', 'AccountSubsetController@companySubset');
        $router->resource('account_subset', 'AccountSubsetController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);
    });

Route::namespace('\App\Http\Controllers\Member')
    ->middleware(["auth:member", 'mojito.permission'])
    ->group(function ($router) {
        $router->get('member/company_account/password_validate', 'AccountSubsetController@passwordValidate');
        $router->resource('member/company_account', 'CompanyAccountController', ['only' =>
            ['index', 'show', 'store', 'update', 'destroy']
        ]);
    });
