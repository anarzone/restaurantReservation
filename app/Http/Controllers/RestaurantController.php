<?php

namespace App\Http\Controllers;

use App\Restaurant;
use Illuminate\Http\Request;

class RestaurantController extends Controller
{
    /**
    * Displaying restaurants and assigned halls
     */
    public function index(){
        $restaurants = Restaurant::whereNull('deleted_at')->with('halls')->get();
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
}
