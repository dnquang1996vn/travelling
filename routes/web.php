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

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile/{user_id}', 'ProfileController@show')->name('profile');
Route::post('profile/{user_id}/update','ProfileController@update')->name('updateProfile');
Route::post('profile/{user_id}/upload','ProfileController@upload')->name('uploadAvatar');
Route::get('trip/{trip_id}','TripController@show')->name('trip');
Route::get('/demo', function () {
    return view('demo');
});
Route::post("/fuck",'ProfileController@upload');
Route::get('load','ProfileController@load')->name('load');
//Create Trip Route
Route::get('create_trip','CreateTripController@create');
Route::post('create_trip','CreateTripController@store');
