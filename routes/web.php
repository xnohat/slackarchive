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

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/test', 'TestController@index');

Route::post('/slack','SlackController@slack');
Route::get('/slack','SlackController@slack');

Route::post('/viewhistory', 'HistoryController@setviewingchannel');
Route::get('/viewhistory', 'HistoryController@show');

Route::get('/searchhistory', 'HistoryController@search');