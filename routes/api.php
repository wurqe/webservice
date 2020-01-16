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
  Route::get('login/facebook', 'Auth\FacebookOauthController@redirectToProvider');
  Route::get('login/facebook/callback', 'Auth\FacebookOauthController@handleProviderCallback');
  Route::post('login', 'Auth\LoginController@login');
  Route::post('register', 'Auth\RegisterController@register');
  // test resource
  // Route::resource('tests', 'TestController');

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
    Route::resource('jobs', 'WorkController');

    // endpoints
    Route::put('services/hire/{invitation}', 'ServiceController@hire');

    // test resource
    Route::resource('tests', 'TestController');
  });
});
