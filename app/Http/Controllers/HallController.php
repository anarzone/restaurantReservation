<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Plan;
use App\PlanTable;
use App\Restaurant;
use App\Table as Hall_Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HallController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index(Request $request){
        if ($request->has('restaurant')){
            $halls = Hall::with('reservations')
                        ->where('restaurant_id', $request->restaurant)
                        ->whereNull('deleted_at')
                        ->paginate(10);
        }else{
            $halls = Hall::with('reservations')
                ->whereNull('deleted_at')
                ->paginate(10);
        }
        return view('admin.pages.halls.index', [
            'halls' => $halls
        ]);
    }

    /**
    * Display hall create page with restaurants
     */
    public function create(){
        $restaurants = Restaurant::whereNull('deleted_at')->whereIn('id', Auth::user()->inGroupRestaurants())->with('halls')->get();

        return view('admin.pages.halls.create', ['restaurants' => $restaurants]);
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

        $request->session()->flash('message', 'Zal yaradıldı');

        return response()->json([
            'status' => Response::HTTP_CREATED,
            'data'   => ['halls' => $hall_created, 'tables' => $tables_created]
        ]);
    }

    public function edit(Request $request, Hall $hall){
        return view('admin.pages.halls.edit', ['hall' => $hall, 'has_reservation' => $request->has_reservation]);
    }

    public function update(Request $request, Hall $hall){
        $rules = [
            'name'    => 'required|string|min:2',
        ];

        $messages = [
            'name.required' => 'Zal adını yazmamısınız.'
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors());
        }

        $request->validate([
            'name' => 'required|string'
        ]);

        $hall->update($request->all());
        return redirect()->route('admin.halls.index')->with('message', 'Zal yeniləndi');
    }

    public function destroy(Hall $hall){
        if(isset($hall->reservations[0])){
            return response()->json([
                'message' => 'Bu zalda aktiv rezervasiya var'
            ], Response::HTTP_OK);
        }
        $rest = Restaurant::where('id', $hall->restaurant_id)->with('halls')->first();
        if(!isset($rest->halls[0])) $rest->update(['status' => Restaurant::NOT_AVAILABLE]);
        $hall->delete();

        return response()->json([],Response::HTTP_NO_CONTENT);
    }

}
