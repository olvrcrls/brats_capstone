<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'HomeController@index');
Route::get('/about', function () {
		return view('about');
});
Route::get('/noschedule', 'HomeController@fail');
Route::post('/travel_schedules', 'ScheduleController@show');
Route::get('/routes', 'RouteController@index');
Route::get('/route/{route}', 'ScheduleController@route_show');

Route::post('/book_seats', 'TransactionController@input');

//API

Route::post('/api/seats', 'SeatController@ajaxRetrieve');