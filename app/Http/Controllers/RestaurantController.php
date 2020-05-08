<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Table as Hall_Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RestaurantController extends Controller
{
    /**
    * Displaying restaurants and assigned halls
     */
    public function index(){

        $restaurants = Restaurant::whereNull('deleted_at')
                                ->whereIn('id', Auth::user()->inGroupRestaurants())
                                ->where('status', '=', Restaurant::AVAILABLE)
                                ->with('halls')->get();

        return view('admin.pages.restaurants.index',
            ['restaurants' => $restaurants]);
    }

    public function create(Request $request){
        return view('admin.pages.restaurants.create_restaurant');
    }

    public function store(Request $request){
        $request->validate([
           'name' => 'required|string|min:2|unique:restaurants',
           'address' => 'nullable|string'
        ]);
        $request->merge(['status'=>Restaurant::NOT_AVAILABLE]);
        $restaurant = Restaurant::create($request->all());

        $related_group = Auth::user()->groups[0];
        $related_group->restaurants()->attach($restaurant->id);

        return redirect()->back();
    }
}
