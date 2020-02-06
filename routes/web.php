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
    return view('welcome');
});

Auth::routes();

Route::middleware('auth')->group(function () {
    Route::get('/home', 'HomeController@index')->name('home');

    Route::group(['prefix' => 'acl'], function () {
        Route::get('/', 'AclController@index')->name('acl.index');
        Route::resource('modules', 'ModuleController', ['as' => 'acl']);
        Route::resource('profiles', 'ProfileController', ['as' => 'acl']);
        Route::put('profiles/{id}/permissions', 'ProfileController@updatePermissions')->name('acl.profiles.updatePermissions');
        Route::put('modules/{id}/profiles', 'ModuleController@updateProfiles')->name('acl.modules.updateProfiles');
    });
});
