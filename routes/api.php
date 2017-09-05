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

Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout');
Route::post('register', 'Auth\RegisterController@register');

Route::post('cms', 'CmsController@store');

Route::group(['middleware' => 'auth:api'], function() {
    Route::get('cms', 'CmsController@index');
    Route::get('cms/{slug}', 'CmsController@getCmsByID');
    
    Route::put('cms/{slug}', 'CmsController@update');
    Route::delete('cms/{slug}', 'CmsController@delete');
});
