<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RestaurantController extends Controller
{
    /**
    * Displaying restaurants and assigned halls
     */
    public function index(){
        $restaurants = Restaurant::whereNull('deleted_at')->where('status', '=', Restaurant::AVAILABLE)->with('halls')->get();
        $table_total = \DB::table('tables')
            ->groupBy('hall_id')
            ->selectRaw('count(*) as totalTables, hall_id')
            ->get();


        $tables_by_hall_id = [];
        foreach ($table_total as $table){
            $tables_by_hall_id[$table->hall_id] = $table->totalTables;
        }

        return view('admin.pages.restaurants.index',
            ['restaurants' => $restaurants, 'tables_by_hall_id' => $tables_by_hall_id]);
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
        Restaurant::create($request->all());

        return back(Response::HTTP_CREATED);
    }
}
