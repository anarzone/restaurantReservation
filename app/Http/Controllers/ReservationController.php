<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
use App\Table as HallTable;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $reservation = app(Reservation::class)->newQuery();
        if ($request->has('status')){
            $request->validate(['status' => 'integer']);
            $reservation->where('reservations.status', $request->status);
        }

        $user_restaurants = array_column(Auth::user()->groups[0]->restaurants->toArray(), 'id');

        $result = $reservation->where('status', '!=', Reservation::STATUS_DONE)
                              ->whereIn('res_restaurant_id', $user_restaurants)
                              ->with('halls')
                              ->with('restaurants')
                              ->with('table')->latest()->paginate(10)->appends('status', $request->status);

        $customers = Customer::all();
        $reservations_by_customers = [];

        foreach ($customers as $customer){
            $reservations_by_customers[$customer->id] = count($customer->reservations);
        }


        return view('admin.pages.reservations', [
            'reservations'             => $result,
            'reservations_by_customers' => $reservations_by_customers
        ]);
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

            HallTable::where('id', $request->table_id)->update(['status' => HallTable::TABLE_BOOKED]);

        }elseif ($reserved_table_id && $reserved_table_id != $table_id){
            Reservation::where('id', $reservation_id)
                ->update(['table_id' => $table_id]);
            HallTable::where('id', $reserved_table_id)->update(['status' => HallTable::TABLE_AVAILABLE]);
            HallTable::where('id', $table_id)->update(['status' => HallTable::TABLE_BOOKED]);
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
        $result = '';

        if($request->has('archive')){
            $result = Reservation::where('status', Reservation::STATUS_DONE)
                                ->with('halls')
                                ->with('restaurants')
                                ->with('table')
                                ->whereBetween('datetime', [$from->toDateTimeString(), $to->toDateTimeString()])
                                ->paginate(10);
            return view('admin.pages.reservations_archive', ['reservations' => $result ]);
        }else{
            $result = Reservation::with('halls')
                                ->with('restaurants')
                                ->with('table')
                                ->whereBetween('datetime', [$from->toDateTimeString(), $to->toDateTimeString()])
                                ->where('status', '!=', Reservation::STATUS_DONE)
                                ->paginate(10);
            return view('admin.pages.reservations', ['reservations' => $result ]);
        }
    }

    public function updateStatus(Request $request){
        $request->validate([
           'reservation_id' => 'required|integer',
        ]);

        if($request->table_id){
            $request->validate([
                'table_id' => 'required|integer',
            ]);

            Reservation::find($request->reservation_id)->update(['status' => Reservation::STATUS_DONE]);
        }


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

    public function updateDate($res_id, Request $request){
        $rules = [
            'date' => 'required|date'
        ];

        $messages = [
            'date'      => 'Doğru formatda daxil etməmisiniz',
            'required'  => 'Tarix boş ola bilməz'
        ];

        Validator::make($request->all(), $rules, $messages);

        Reservation::where('id', $res_id)->update(['datetime' => $request->date]);

        return response()->json([
            'message' => 'Tarix uğurla yeniləndi.'
        ], Response::HTTP_CREATED);
    }

    public function updateTable($res_id, Request $request){
        $rules = [
            'table_id' => 'required|numeric',
            'date'     => 'required|date'
        ];

        $messages = [
            'required.table_id'  => 'Stol seçilməyib',
            'date'               => 'Yalnış format'
        ];

        Validator::make($request->all(), $rules, $messages);

        $reserved = Reservation::where('datetime', $request->date)
                                ->where('table_id', $request->table_id)
                                ->where('status', '!=', Reservation::STATUS_DONE)
                                ->first();
        if($reserved){
            $message = '';
            $data = 'Masa bu tarixdə artıq tutulmuşdur';
        }else{
            Reservation::where('id', $res_id)
                        ->update([
                                    'table_id' => $request->table_id,
                                    'status' => Reservation::STATUS_ACCEPTED
                                ]);

            $table = HallTable::find($request->table_id);

            $message = 'Success';
            $data = $table->table_number . ' nömrəli masa  seçildi.';
        }


        return response()->json([
            'message' => $message,
            'data'    => $data,
        ], Response::HTTP_CREATED);
    }

    public function getTableReservations($table_id){

        $related_reservations = Reservation::where('table_id', $table_id)->get();
        $table = HallTable::find($table_id);
        return response()->json([
            'message' => 'Success',
            'data' => [
                'reservations' => $related_reservations,
                'table' => $table
            ],
        ], Response::HTTP_OK);
    }
}
