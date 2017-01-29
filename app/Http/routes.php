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
Route::get('/about', 'HomeController@about');
Route::get('/noschedule', 'HomeController@fail');
Route::post('/travel_schedules', 'ScheduleController@show');
Route::get('/routes', 'RouteController@index');
Route::get('/route/{route}', 'ScheduleController@route_show');
Route::get('/bus/passengers/terms_and_agreement', 'HomeController@termsAndAgreement');
Route::post('/book_seats', 'TransactionController@input');
Route::post('/book_seats/iterate', 'TransactionController@view');
Route::post('/transaction/complete', 'TransactionController@store');
Route::get('/transaction/voucher/{purchase}/transaction/{customer}/viewprint', 'EmailController@index');
Route::get('/voucher/save/{purchase}/{customer}/VoucherPDF', 'EmailController@save');
Route::get('/voucher/print/{purchase}/{customer}/VoucherPDF', 'EmailController@printDocument');
Route::get('/manage/trip', 'TransactionController@manage');
Route::post('/manage/trip/retrieve', 'TransactionController@retrieve')->middleware('web');
Route::post('/manage/trip/cancel/request', 'TransactionController@cancel');



/* API URLs */
Route::post('/api/seats', 'SeatController@ajaxRetrieve');
Route::post('/api/seats/check', 'SeatController@ajaxCheck');
Route::put('/api/seats/update/tentative', 'SeatController@ajaxUpdate_tentative');
Route::put('/api/seats/update/queue', 'SeatController@ajaxUpdate_queue');
Route::put('/api/seats/update/unqueue', 'SeatController@ajaxUpdate_unqueue');
Route::put('/api/seats/update/all/unqueue', 'SeatController@ajaxUpdate_unqueue_all');
Route::put('/api/seats/update/all/available', 'SeatController@ajaxUpdate_setAvailable_all');
Route::get('/api/routes/fetch', 'RouteController@fetch');
Route::get('/api/schedule/days/fetch', 'ScheduleController@fetchDays');
/* TEST */
Route::get('/test/email', 'TestController@email');
Route::get('/test/pdf', 'TestController@pdf');