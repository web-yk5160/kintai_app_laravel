<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/', 'Auth\LoginController@showLoginForm')->name('route');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/edit', 'UserController@edit')->name('edit');
Route::get('/show', 'UserController@show')->name('show');
Route::post('/update/{id}', 'UserController@update')->name('update');
Route::post('attendance/start_time', 'AttendanceController@startTime')->name('start_time');
Route::post('attendance/end_time', 'AttendanceController@endTime')->name('end_time');