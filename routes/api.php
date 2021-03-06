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

Route::group(['middleware' => ['localization']], function(){
  // Route::get('payment/callback/{code}', 'PaymentController@callback');
  Route::get('payments/pay/init', 'PaymentController@Astore');
  Route::get('payments/verify/{trxref}', 'PaymentController@Averify');
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
  // Route::group(['middleware' => ['guest']], function(){
    Route::get('services', 'ServiceController@index');
  // });
  // auth endpoints
  Route::group(['middleware' => ['auth:api']], function(){
    Route::resource('services', 'ServiceController')->except(['index']);

    // service endpoints
    Route::put('services/hire/{invitation}', 'ServiceController@hire');
    // jobs endpoints
    Route::put('jobs/complete/{work}',        'WorkController@complete');
    Route::post('jobs/rate/{work}',           'WorkController@rate');
    Route::post('jobs/{work}/pay',            'WorkController@pay');
    // user endpoints
    Route::get('users/wallet/balance',        'UserController@balance');
    Route::get('users/wallet/details',        'UserController@wallet');
    Route::get('users/jobs',                  'UserController@jobs');
    Route::get('users/services/stats',        'UserController@serviceStats');
    Route::get('users/services',              'UserController@services');
    Route::get('users/extras',                'UserController@extras');
    Route::get('whoami',                      'UserController@whoami');
    Route::get('logout',                      'Auth\LoginController@logout');
    Route::put('userprofileupdate',           'UserController@UserProfileUpdate');
    Route::post('kycdocs',                    'UserController@Kycdocs');
    // payment
    Route::get('transactions',                'PaymentController@transactionHistory');
    Route::post('payments/verify',            'PaymentController@verify');
    Route::get('payments/options',            'PaymentController@options');
    Route::put('bids',                        'BidController@attemptBid');
    // resources
    Route::apiResources([
      'users'                           => 'UserController',
      'settings'                        => 'SettingController',
      'categories'                      => 'CategoryController',
      'invitations'                     => 'InvitationController',
      'notifications'                   => 'NotificationController',
      'applications'                    => 'ServiceApplicationController',
      'notifications'                   => 'NotificationController',
      'jobs'                            => 'WorkController',
      'payments'                        => 'PaymentController',
      'bids'                            => 'BidController',
      'metas'                           => 'MetaController',
      'reviews'                         => 'ReviewController',
      'tests'                           => 'TestController',
    ]);
  });
  Route::get('users/count', 'TestController@userCount');
  Route::get('app/reset/0000', 'TestController@reset');
});
