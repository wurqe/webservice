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

Route::group(['middleware' => 'localization'], function(){
  // api endpoints
  Route::post('login', 'UserController@login');
  Route::post('register', 'UserController@create');

  // auth endpoints
  Route::group(['middleware' => ['auth:api']], function(){
    // resources
    Route::resource('users', 'UserController');
    Route::resource('settings', 'SettingController');
    Route::resource('interests', 'InterestController');
    Route::resource('categories', 'CategoryController');
    Route::resource('invitations', 'InvitationController');
    Route::resource('notifications', 'NotificationController');
    Route::resource('services', 'ServiceController');
    Route::resource('works', 'WorkController');
  });
});
