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


Route::get('/','HomeController@index');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/profile/{user_id}', 'ProfileController@show')->name('profile');
Route::post('profile/{user_id}/update', 'ProfileController@update')->name('updateProfile');
Route::post('profile/{user_id}/upload', 'ProfileController@upload')->name('uploadAvatar');
Route::get('trip/{trip_id}', 'TripController@show')->name('trip');
Route::post('trip/follow', 'TripController@follow');
Route::post('trip/unfollow', 'TripController@unfollow');
Route::post('trip/joinTrip', 'TripController@joinTrip');
Route::post('trip/cancelRequest', 'TripController@cancelRequest');
Route::post('trip/acceptRequest', 'TripController@acceptRequest');
Route::post('trip/denyRequest', 'TripController@denyRequest');
Route::post('trip/outTrip', 'TripController@outTrip');
Route::post('trip/kick', 'TripController@kick');
Route::post('trip/startTrip', 'TripController@startTrip');
Route::post('trip/finishTrip', 'TripController@finishTrip');
Route::post('trip/cancelTrip', 'TripController@cancelTrip');

Route::post('comment/image/delete','UploadController@delete');
Route::post('comment/add','CommentController@add');

Route::get('demo','UploadController@dropzone');
Route::post('/load','UploadController@dropzoneStore');
Route::post('/upload/delete', 'UploadController@delete');
Route::get('view','UploadController@view');
Route::post('/load2','UploadController@load');
Route::get('server-images/{comment_id}', 'UploadController@getServerImages');

//Create Trip Route
Route::group(['middleware' => ['user']], function () {
    Route::get('create_trip','CreateTripController@create');
    Route::post('create_trip','CreateTripController@store');
    Route::get('edit_trip/{trip_id}','CreateTripController@editForm');
    Route::post('edit_trip/{trip_id}','CreateTripController@editTrip');
});

