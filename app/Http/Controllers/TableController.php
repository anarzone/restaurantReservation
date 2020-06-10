<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Plan;
use App\PlanTable;
use App\Reservation;
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
            'rest_id'        => 'required|numeric',
            'people_amount'  => 'required|integer'
        ]);

        $table_created =
            HallTable::create([
                'table_number'  => $request->table_number,
                'people_amount'  => $request->people_amount,
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
                HallTable::where('id', '=', $request->res_table_id)
                    ->update(['status' => Hall::TABLE_AVAILABLE]);
            }

        }else{
            $table_status = Hall::TABLE_AVAILABLE;
            $reservation_status = Reservation::STATUS_REJECTED;

        }

        $table_update = HallTable::where('id', $request->table_id)
            ->update(['status' => $table_status]);

        $reservation_update = Reservation::where('id', $request->res_id)
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
     * @param $id
     * @return JsonResponse
     */
    public function destroy($id)
    {
        $message = null;
        $table = HallTable::find($id);

        \DB::table('plan_tables')->where('table_id', $id)->delete();
        $table->delete();

        $message = 'Successfully deleted';

        return response()->json([
            'message' => $message,
        ]);
    }

    public function get_by_hall_id(Request $request, $hall_id){
        $tables = HallTable::where('hall_id', $hall_id)->orderBy('status', 'asc')->get();
        $table_have_reservations = $this->get_reservation_statuses_by_hall_id($hall_id);

        $has_plan = Hall::where('id', $hall_id)->with('plan')->first()->plan;
        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => [
                'tables' => $tables,
                'has_plan' => $has_plan,
                'table_have_reservations' => $table_have_reservations,
            ]
        ]);
    }

    public function get_plan_tables_by_hall_id($hall_id){
        $table_have_reservations = $this->get_reservation_statuses_by_hall_id($hall_id);

        $tables = PlanTable::where('hall_id', $hall_id)->get();
        $plan = Plan::where('hall_id', $hall_id)->first();
        $plan_image = $plan ? $plan->img_name : '';
        return response()->json([
           'data' => [
               'tables' => $tables,
               'plan_image' => $plan_image,
               'table_have_reservations' => $table_have_reservations,
           ]
        ]);
    }

    public function change_number(Request $request){
        $request->validate([
           'table_number' => 'required|numeric',
           'people_amount' => 'required|numeric'
        ]);
        $table_updated = null;
        $table = HallTable::find($request->table_id);

        $table_updated = HallTable::where('id', $request->table_id)
            ->update(['table_number' => $request->table_number, 'people_amount' => $request->people_amount]);


        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $table_updated
        ]);
    }

    public function get_reservation_statuses_by_hall_id($hall_id){
        $tables = HallTable::where('hall_id', $hall_id)->orderBy('status', 'asc')->get();
        $table_have_reservations = [];
        foreach ($tables as $table){
            if(count($table->reservations) > 0){
                foreach ($table->reservations as $reservation){
                    if ($reservation->status === Reservation::STATUS_ACCEPTED){
                        $table_have_reservations[$table->id] = 1;
                    }
                }
            }else{
                $table_have_reservations[$table->id] = 0;
            }
        }
        return $table_have_reservations;
    }
}
