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

Route::get('/', 'TestController@welcome');
//Clear Cache facade value:
Route::get('/clear-cache', 'TestController@clearCache');
//Reoptimized class loader:
Route::get('/optimize', 'TestController@optimize');
//Route cache:
Route::get('/route-cache', 'TestController@routeCache');
//Clear Route cache:
Route::get('/route-clear', 'TestController@routeClear');
//Clear View cache:
Route::get('/view-clear', 'TestController@viewClear');
//Clear Config cache:
Route::get('/config-cache', 'TestController@configCache');
