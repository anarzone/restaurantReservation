<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::prefix('v1')->namespace('Api\V1')->group(function (){
    Route::get('getRestaurants', 'FormController@getRestaurants')->name('getRestaurants');
    Route::get('getHallsByRestaurantId/{restaurant}', 'FormController@getHallsByRestaurantId')->name('getRestaurants');
});
Route::post('/v1/sendForm', 'FrontController@sendForm');
