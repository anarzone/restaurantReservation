<?php

namespace App\Http\Controllers;

use App\Reservation;
use App\Table;
use App\Table as HallTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\Response as ResponseAlias;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Reservation
     */
    public function index(Request $request)
    {
        $reservation = app(Reservation::class)->newQuery();
        if ($request->has('status')){
            $reservation->where('reservations.status', $request->status);
        };

        $result = $reservation->where('status', '!=', Reservation::STATUS_DONE)->with('halls')->with('restaurants')->with('table')->paginate(10);

        return view('admin.pages.reservations', ['reservations' => $result ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     */
    public function update(Request $request)
    {
        $request->validate([
            'table_id' => 'required|integer',
            'reservation_id' => 'required|integer',
            'reserved_table_id' => 'nullable|integer'
        ]);
        $reserved_table_id = $request->reserved_table_id;
        $table_id = $request->table_id;
        $reservation_id = $request->reservation_id;

        if($reserved_table_id == null){
            Reservation::where('id', $reservation_id)
                ->update(['table_id' => $table_id, 'status' => Reservation::STATUS_ACCEPTED]);

            HallTable::where('id', $request->table_id)->update(['status' => Table::TABLE_BOOKED]);

        }elseif ($reserved_table_id && $reserved_table_id != $table_id){
            Reservation::where('id', $reservation_id)
                ->update(['table_id' => $table_id]);
            HallTable::where('id', $reserved_table_id)->update(['status' => Table::TABLE_AVAILABLE]);
            HallTable::where('id', $table_id)->update(['status' => Table::TABLE_BOOKED]);
        }

        $reservation_affected = Reservation::find($request->reservation_id);

        return response()->json([
            'message' => 'Success',
            'data' =>  $reservation_affected
        ]);
    }

    public function filterByDate(Request $request){
        $rules = [
            'date_from' => 'required|string',
            'date_to' => 'required|string',
        ];

        $messages = [
            'required' => 'Tarixi daxil etməmisiniz',
            'date' => 'Doğru parametrləri daxil etməmisiniz',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        $validator->validate();

        $from = Carbon::create($request->date_from);
        $to = Carbon::create($request->date_to);
        $result = Reservation::with('halls')
                            ->with('restaurants')
                            ->with('table')
                            ->whereBetween('datetime', [$from->toDateTimeString(), $to->toDateTimeString()])
                            ->paginate(10);
        return view('admin.pages.reservations', ['reservations' => $result ]);
    }

    public function updateStatus(Request $request){
        $request->validate([
           'reservation_id' => 'required|integer',
           'table_id' => 'required|integer'
        ]);

        $reservation_status = 0;
        $table_status = 0;

        if($request->status === 'done'){
            $reservation_status = Reservation::STATUS_DONE;
            $table_status = Table::TABLE_AVAILABLE;
        }

        Reservation::find($request->reservation_id)->update(['status' => $reservation_status]);
        Table::find($request->table_id)->update(['status' => $table_status]);

        return response()->json([
            'message' => 'Success',
            'data'    =>  1
        ], Response::HTTP_CREATED);
    }

    public function showArchive(){
        $reservation = app(Reservation::class)->newQuery();

        $result = $reservation->where('status', Reservation::STATUS_DONE)->with('halls')->with('restaurants')->with('table')->paginate(10);

        return view('admin.pages.reservations_archive', ['reservations' => $result ]);
    }
}
