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
  Route::get('login/google', 'Auth\GoogleOauthController@redirectToProvider');
  Route::get('login/google/callback', 'Auth\GoogleOauthController@handleProviderCallback');
  Route::post('login', 'Auth\LoginController@login');
  Route::post('register', 'Auth\RegisterController@register');
  // test resource
  // Route::resource('tests', 'TestController');

  // auth endpoints
  Route::group(['middleware' => ['auth:api']], function(){
    // resources
    Route::put('UpdatePersonalInfo/{id}', 'UpdatePersonalInfoController@update');
    Route::put('ProfilePics/{id}', 'UpdatePersonalInfoController@ProfileImage');
    Route::resource('users', 'UserController');
    Route::resource('settings', 'SettingController');
    Route::resource('interests', 'InterestController');
    Route::resource('categories', 'CategoryController');
    Route::resource('invitations', 'InvitationController');
    Route::resource('notifications', 'NotificationController');
    Route::resource('services', 'ServiceController');
    Route::resource('works', 'WorkController');
    // test resource
    Route::resource('tests', 'TestController');
  });
});
