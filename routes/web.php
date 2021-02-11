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

// Auth
Route::get('login')->name('login')->uses('Auth\LoginController@showLoginForm')->middleware('guest');
Route::post('login')->name('login.attempt')->uses('Auth\LoginController@login')->middleware('guest');
Route::post('logout')->name('logout')->uses('Auth\LoginController@logout');

Route::post('vision')->name('vision.index')->uses('VisionController@index');
Route::get('vision')->name('vision.index')->uses('VisionController@index');

Route::group(['middleware' => 'auth'], function () {
    // Dashboard
    Route::get('/')->name('dashboard')->uses('UserController@index');

    Route::resource('users', 'UserController');
    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');
    Route::resource('categories', 'CategoryController');
    Route::post('categories/update')->uses('CategoryController@updateTree')->name('categories.updateTree');
});
