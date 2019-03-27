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
    //return 1;
    $member = \App\Models\Member::with(['company'])->find(1);
    dd($member->toArray());
    return $member;
});

Route::get('/test', 'AccountSubsetController@companySubset');

Route::get('account_management/{id}', 'AccountController@update');

Route::get('/member', function () {
    return view('member');
});
