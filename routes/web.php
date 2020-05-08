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
    Route::get('restaurants', 'RestaurantController@index')->name('restaurants.index');
    Route::get('restaurants/create', 'RestaurantController@create')->name('restaurants.create');
    Route::get('halls/create', 'HallController@create')->name('halls.create');
    Route::get('users/index', 'AdminController@getUsers')->name('users.index');
    Route::get('users/create', 'AdminController@createUsers')->name('users.create');

    // Groups
    Route::get('/groups/index', 'AdminController@showGroups')->name('groups.index');
    Route::get('/groups/create', 'AdminController@createGroup')->name('groups.create');
    Route::post('/groups/store', 'AdminController@storeGroup')->name('groups.store');
    Route::get('/groups/{group}/edit', 'AdminController@editGroup')->name('groups.edit');
    Route::put('/groups/{group}/update', 'AdminController@updateGroup')->name('groups.update');

    // Roles
    Route::get('/roles', 'AdminController@showRoles')->name('roles.index');
    Route::get('/roles/create', 'AdminController@createRoles')->name('roles.create');
    Route::get('/roles/{role}/edit', 'AdminController@editRoles')->name('roles.edit');
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

//    Restaurants routes
    Route::post('/restaurants/store', 'RestaurantController@store')->name('restaurants.store');

    // Admin routes
    Route::get('/roles/all', 'AdminController@getRoles');
    Route::post('/users/update', 'AdminController@updateUser');
    Route::post('/users/store', 'AdminController@storeUser')->name('users.store');
    Route::get('/admin/getRolesAndGroups', 'AdminController@getRolesAndGroups');

    // Reservation routes
    Route::post('/reservations/update', 'ReservationController@update');
    Route::post('/reservations/status/update', 'ReservationController@updateStatus');
});



