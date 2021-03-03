<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::namespace('Api')->group(function () {
    Route::prefix('product')->name('api.product.')->group(function () {
        Route::get('index', 'ProductController@index')->name('index');
        Route::get('{id}/show', 'ProductController@show')->name('show');
        Route::post('update', 'ProductController@update')->name('update');
        Route::post('store', 'ProductController@store')->name('store');
    });

    Route::prefix('category')->name('api.category.')->group(function () {
        Route::get('index', 'CategoryController@index')->name('index');
        Route::get('{id}/show', 'CategoryController@show')->name('show');
        Route::post('update', 'CategoryController@update')->name('update');
        Route::post('store', 'CategoryController@store')->name('store');
    });
});
