<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Reservation;
use App\Table;
use App\Table as Hall_Table;
use App\Table as HallTable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TableController extends Controller
{


    /**
     * Store a newly created table.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function store(Request $request){
        $request->validate([
            'table_number'   => 'required|numeric',
            'hall_id'        => 'required|numeric',
            'rest_id'  => 'required|numeric',
        ]);

        $table_created =
            Hall_Table::create([
                'table_number'  => $request->table_number,
                'hall_id'       => $request->hall_id,
                'restaurant_id' => $request->rest_id
            ]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $table_created
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @return JsonResponse
     */

    public function update(Request $request){
        $table_status = null;
        $reservation_status = 0;
        if($request->book == 1){
            $table_status = Hall::TABLE_BOOKED;
            $reservation_status = Reservation::STATUS_ACCEPTED;

            if($request->res_table_id){
                \DB::table('tables')
                    ->where('id', '=', $request->res_table_id)
                    ->update(['status' => Hall::TABLE_AVAILABLE]);
            }

        }else{
            $table_status = Hall::TABLE_AVAILABLE;
            $reservation_status = Reservation::STATUS_REJECTED;

        }


        $table_update = \DB::table('tables')
            ->where('id', '=', $request->table_id)
            ->update(['status' => $table_status]);

        $reservation_update = Reservation::where('id', '=', $request->res_id)
            ->update([
                'status'   => $reservation_status,
                'table_id' => $reservation_status == Reservation::STATUS_REJECTED ? null : $request->table_id
            ]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => [
                'table' => $table_update,
                'reservation' => $reservation_update
            ]
        ]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param Table $table
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $table = HallTable::find($id)->delete();

        return response()->json([
            'message' => 'Successfully deleted',
        ]);
    }

    public function get_by(Request $request){
        $tables =   \DB::table('tables')
            ->where('hall_id', '=', $request->hall_id)
            ->where('restaurant_id', '=', $request->rest_id)
            ->where('status', '=', Hall::TABLE_AVAILABLE)
            ->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $tables
        ]);
    }

    public function get_by_hall_id(Request $request){
        $tables = \DB::table('tables')->where('hall_id', '=', $request->hall_id)->get();
        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $tables
        ]);
    }

    public function change_number(Request $request){
        $table_updated = \DB::table('tables')
            ->where('id', '=', $request->table_id)
            ->update(['table_number' => $request->table_number]);

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $table_updated
        ]);
    }
}
