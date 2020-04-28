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


Route::get('/', 'FrontController@index');
Route::post('/check_table', 'FrontController@checkTableAvailability');
Route::get('/getHallsByRestId/{restaurant_id}', 'FrontController@getHallsByRestId');

// Admin routes
Route::prefix('ambrn-admin')->group(function (){
    Auth::routes();
});

Route::prefix('admin')->name('admin.')->group(function (){
    Route::get('/', 'ReservationController@index')->name('dashboard');
    Route::get('reservations', 'ReservationController@index')->name('reservations.index');
    Route::get('restaurants', 'RestaurantController@index')->name('restaurants.index');
    Route::get('halls/create', 'HallController@create')->name('halls.create');
});

// Hall routes

Route::post('/halls/update/name', 'HallController@update_hall_name');
Route::post('/halls/store', 'HallController@store');


// Table routes

Route::post('/tables/get_by', 'TableController@get_by');
Route::post('/tables/update', 'TableController@update');
Route::delete('/tables/destroy/{id}', 'TableController@destroy');
Route::post('/tables/store', 'TableController@store');
Route::post('/get_tables_by_hall_id', 'AdminController@get_tables_by_hall_id');
Route::post('/change_table_number', 'AdminController@change_table_number');


