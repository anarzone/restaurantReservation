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

Route::prefix('admin')->middleware('auth')->name('admin.')->group(function (){
    Route::get('/', 'AdminController@dashboard')->name('dashboard');
    Route::get('reservations', 'ReservationController@index')->name('reservations.index');
    Route::get('reservations/archive', 'ReservationController@showArchive')->name('reservations.archive');
    Route::post('reservations/filterByDate', 'ReservationController@filterByDate')->name('filter.date');

    // Users
    Route::get('users/index', 'UserController@index')->name('users.index');
    Route::get('users/create', 'UserController@create')->name('users.create');
    Route::get('users/profile', 'AdminController@showProfile')->name('users.profile');
    Route::post('users/profile/update', 'AdminController@updateProfile')->name('users.profile.update');

    // Groups
    Route::get('/groups/index', 'GroupController@index')->name('groups.index');
    Route::get('/groups/create', 'GroupController@create')->name('groups.create');
    Route::post('/groups/store', 'GroupController@store')->name('groups.store');
    Route::get('/groups/{group}/edit', 'GroupController@edit')->name('groups.edit');
    Route::put('/groups/{group}/update', 'GroupController@update')->name('groups.update');
    Route::delete('/groups/destroy/{group}', 'GroupController@destroy')->name('groups.destroy');

    // Roles
    Route::get('/roles', 'AdminController@showRoles')->name('roles.index');
    Route::get('/roles/create', 'AdminController@createRoles')->name('roles.create');
    Route::get('/roles/{role}/edit', 'AdminController@editRoles')->name('roles.edit');

    // Restaurants
    Route::get('restaurants', 'RestaurantController@index')->name('restaurants.index');
    Route::get('restaurants/create', 'RestaurantController@create')->name('restaurants.create');
    Route::get('restaurants/all', 'RestaurantController@getList')->name('restaurants.list');
    Route::get('restaurants/{restaurant}/edit', 'RestaurantController@edit')->name('restaurants.edit');
    Route::put('restaurants/{restaurant}/update', 'RestaurantController@update')->name('restaurants.update');
    Route::delete('restaurants/destroy/{restaurant}', 'RestaurantController@destroy')->name('restaurants.destroy');

    // Halls
    Route::get('halls', 'HallController@index')->name('halls.index');
    Route::get('halls/create', 'HallController@create')->name('halls.create');
    Route::get('halls/{hall}/edit', 'HallController@edit')->name('halls.edit');
    Route::put('halls/{hall}/update', 'HallController@update')->name('halls.update');
    Route::delete('halls/destroy/{hall}', 'HallController@destroy')->name('halls.destroy');

});

// Hall routes

Route::post('/halls/update/name', 'HallController@update_name');
Route::post('/halls/store', 'HallController@store');



Route::middleware('auth')->group(function (){
    // Table routes
    Route::get('/tables/get_by_hall_id/{hall_id}', 'TableController@get_by_hall_id');
    Route::post('/tables/update', 'TableController@update');
    Route::delete('/tables/destroy/{id}', 'TableController@destroy');
    Route::post('/tables/store', 'TableController@store');
    Route::post('/tables/change_number', 'TableController@change_number');

    // Restaurants routes
    Route::post('/restaurants/store', 'RestaurantController@store')->name('restaurants.store');

    // Admin routes
    Route::get('/roles/all', 'AdminController@getRoles');
    Route::get('/admin/getRolesAndGroups', 'AdminController@getRolesAndGroups');

    // Users
    Route::post('/users/update', 'UserController@update');
    Route::post('/users/store', 'UserController@store')->name('users.store');
    Route::delete('/users/destroy/{user}', 'UserController@destroy');

    // Reservation routes
    Route::post('/reservations/update', 'ReservationController@update');
    Route::post('/reservations/status/update', 'ReservationController@updateStatus');
});



