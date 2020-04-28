<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Reservation;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\Console\Helper\Table;

class FrontController extends Controller
{
    public function index(){
        $data = Restaurant::select('id','name')->whereNull('deleted_at')->get();
        return view('front.form', ['restaurants' => $data]);
    }

    public function checkTableAvailability(Request $request){
        $request->validate([
           'firstname' => 'required|string|min:2',
           'lastname' => 'required|string|min:2',
           'phone' => 'required|string',
           'restaurant_id' => 'required|alpha_num',
           'hall_id' => 'required|alpha_num',
           'people' => 'required|alpha_num|min:1',
           'reservation_date' => 'required|date',
        ]);

        $data = Reservation::create([
            'res_firstname' => $request->firstname,
            'res_lastname' => $request->lastname,
            'res_phone' => $request->phone,
            'res_people' => $request->people,
            'res_restaurant_id' => $request->restaurant_id,
            'res_hall_id' => $request->hall_id,
            'datetime' => $request->reservation_date,
            'status' => Reservation::STATUS_PENDING
        ]);

       return response()->json(['message'=>'success', 'data' => $data], Response::HTTP_CREATED);
    }

    public function getHallsByRestId($rest_id){
        $halls = Hall::select('id','name')->where('restaurant_id','=', $rest_id)->get();
        return response()->json([
            'message'=> 'success',
            'data' => $halls
        ],
        Response::HTTP_OK);
    }
}
