<?php

namespace App\Http\Controllers\Api\V1;

use App\Hall;
use App\Http\Controllers\Controller;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class FormController extends Controller
{
    public function getRestaurants(){
        $restaurants = Restaurant::select('id','name')->whereNull('deleted_at')->where('status', '1')->get();
        return response()->json([
            'message' => 'Success',
            'data' => [
                'restaurants' => $restaurants
            ]
        ]);
    }

    public function getHallsByRestaurantId(Restaurant $restaurant){
        $halls = $restaurant->halls();
        return response()->json(['message'=> 'success', 'data' => $halls], Response::HTTP_OK);
    }
}
