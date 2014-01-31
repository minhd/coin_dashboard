<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', 'HomeController@dash');
Route::get('/lite/{task}', 'HomeController@litecoin');
Route::get('/doge/{task}', 'HomeController@dogecoin');
Route::get('/market', 'HomeController@market_rates');