<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Reservation;
use App\Restaurant;
use App\Table as Hall_Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class AdminController extends Controller
{
    public function __construct()
    {
        return $this->middleware('auth');
    }

    /**
     * Displaying application layout
     */

    public function index(Request $request){
        return view('admin.layouts.app');
    }


    public function get_tables_by_hall_id(Request $request){
        $tables = \DB::table('tables')->where('hall_id', '=', $request->hall_id)->get();
        return response()->json([
           'status' => Response::HTTP_OK,
           'data'   => $tables
        ]);
    }

    public function change_table_number(Request $request){
        $table_updated = \DB::table('tables')
                            ->where('id', '=', $request->table_id)
                            ->update(['table_number' => $request->table_number]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $table_updated
        ]);
    }


}
