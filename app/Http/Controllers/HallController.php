<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Restaurant;
use App\Table as Hall_Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class HallController extends Controller
{
    public function index(){
        return view('admin.pages.halls.index', ['halls' => Hall::whereNull('deleted_at')->paginate(10)]);
    }

    /**
    * Display hall create page with restaurants
     */
    public function create(){
        $restaurants = Restaurant::whereNull('deleted_at')->whereIn('id', Auth::user()->inGroupRestaurants())->with('halls')->get();

        return view('admin.pages.restaurants.create_hall', ['restaurants' => $restaurants]);
    }

    public function store(Request $request){
        $request->validate([
            'rest_id'    => 'required|numeric',
            'hall_name' => 'required',
            'tables'    => 'required'
        ]);

        $restaurant = Restaurant::find($request->rest_id);
        if($restaurant->status == 0){
            Restaurant::where('id', '=', $request->rest_id)->update(['status'=>Restaurant::AVAILABLE]);
        }

        $hall_created = Hall::create([
            'name' => $request->hall_name,
            'restaurant_id' => $request->rest_id
        ]);

        foreach ($request->tables as $table){

            Hall_Table::create([
                'table_number'   => $table['table_number'],
                'people_amount'  => $table['people_amount'],
                'hall_id'        => $hall_created->id,
                'restaurant_id' => $request->rest_id
            ]);
        }

        $tables_created = Hall_Table::where('hall_id', $hall_created->id)->get();
        return response()->json([
            'status' => Response::HTTP_CREATED,
            'data'   => ['halls' => $hall_created, 'tables' => $tables_created]
        ]);
    }

    public function edit(Hall $hall){
        return view('admin.pages.halls.edit', ['hall' => $hall]);
    }

    public function update(Request $request, Hall $hall){
        $request->validate([
            'name' => 'required|string'
        ]);

        $hall->update($request->all());
        return back()->with('message', 'Yeniləndi');
    }

    public function destroy(Hall $hall){
        if(isset($hall->reservations[0])){
            return response()->json([
                'message' => 'Bu zalda aktiv rezervasiya var'
            ], Response::HTTP_OK);
        }
        $hall->delete();
        return response()->json([],Response::HTTP_NO_CONTENT);
    }
}
