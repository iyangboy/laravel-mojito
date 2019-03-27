<?php

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
    //return view('welcome');
    return redirect('/mojito#/admin/login');
});

Route::get('/test1', function () {
    dd(config('mojito.super_admin.auth'));
});

Route::get('/test', 'member\CompanyAccountController@index');

Route::get('account_management/{id}', 'AccountController@update');

Route::get('/member', function () {
    return view('member');
});
