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
Route::get('login/facebook', 'FacebookOauthController@redirectToProvider');
Route::get('login/facebook/callback', 'FacebookOauthController@handleProviderCallback');
Route::get('login/google', 'GoogleOauthController@redirectToProvider');
Route::get('login/google/callback', 'GoogleOauthController@handleProviderCallback');
Route::put('UpdatePersonalInfo/{id}', 'UpdatePersonalInfoController@update');
