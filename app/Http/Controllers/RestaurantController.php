<?php

namespace App\Http\Controllers;

use App\Restaurant;
use App\Table as Hall_Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class RestaurantController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    /**
    * Displaying restaurants and assigned halls
    */
    public function index(){

        $restaurants = Restaurant::whereNull('deleted_at')
        ->whereIn('id', Auth::user()->inGroupRestaurants())
        ->where('status', '=', Restaurant::AVAILABLE)
        ->with('halls')->get();

        return view('admin.pages.restaurants.index', [
            'restaurants' => $restaurants,
        ]);
    }

    public function create(Request $request){
        return view('admin.pages.restaurants.create');
    }

    public function store(Request $request){
        $rules = [
            'name'    => 'required|string|min:2',
            'address' => 'nullable|string'
        ];

        $messages = [
            'name.required' => 'Restoran adını yazmamısınız.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $request->merge(['status'=>Restaurant::NOT_AVAILABLE]);
        $restaurant = Restaurant::create($request->all());

        $related_group = Auth::user()->groups[0];
        $related_group->restaurants()->attach($restaurant->id);

        return redirect()->route('manage.restaurants.list')->with('message', 'Restoran yaradıldı');


    }

    public function getList(){
        $restaurants = Restaurant::whereNull('deleted_at')
            ->whereIn('id', Auth::user()->inGroupRestaurants())
            ->with('halls')->get();
        return view('admin.pages.restaurants.list', ['restaurants' => $restaurants]);
    }

    public function edit(Restaurant $restaurant){
        return view('admin.pages.restaurants.edit', ['restaurant' => $restaurant]);
    }

    public function update(Request $request, Restaurant $restaurant){
        $rules = [
            'name'    => 'required|string|min:2',
            'address' => 'nullable|string'
        ];

        $messages = [
            'name.required' => 'Restoran adını yazmamısınız.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $restaurant->update($request->all());

        return redirect()->route('manage.restaurants.list')->with('message', 'Restoran yeniləndi');
    }

    public function destroy(Request $request, Restaurant $restaurant){
        if($request->status == Restaurant::AVAILABLE){
            if(isset($restaurant->reservations[0])){
                return response()->json([
                  'message' => 'Bu restoranda aktiv rezervasiya var'
                ], Response::HTTP_OK);
            }
            if(isset($restaurant->groups)){
                $restaurant->groups()->detach();
            }
        }
        $restaurant->delete();

        return response()->json([],Response::HTTP_NO_CONTENT);
    }
}
