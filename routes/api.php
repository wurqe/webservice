<?php

use Illuminate\Http\Request;
use Artisan;

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
  // guest routes
  Route::group(['middleware' => ['guest']], function(){
    Route::get('services', 'ServiceController@index');
  });
  // auth endpoints
  Route::group(['middleware' => ['auth:api']], function(){
    // resources
    Route::apiResources([
      'users'             => 'UserController',
      'settings'          => 'SettingController',
      'interests'         => 'InterestController',
      'categories'        => 'CategoryController',
      'invitations'       => 'InvitationController',
      'notifications'     => 'NotificationController',
      'jobs'              => 'WorkController',
      'payments'          => 'PaymentController',
      'metas'             => 'MetaController',
    ]);
    Route::resource('services', 'ServiceController')->except(['index']);

    // service endpoints
    Route::put('services/hire/{invitation}', 'ServiceController@hire');
    // jobs endpoints
    Route::put('jobs/complete/{work}', 'WorkController@complete');
    Route::post('jobs/rate/{work}', 'WorkController@rate');
    Route::post('jobs/{work}/pay', 'WorkController@pay');
    Route::get('users/wallet/balance', 'userController@balance');
    Route::get('transactions', 'PaymentController@transactionHistory');
    Route::get('users/wallet/details', 'UserController@wallet');

    // test resource
    Route::resource('tests', 'TestController');
  });
  Route::get('app/reset/0000', function(){
    // Artisan::call('app:reset');
  });
});
