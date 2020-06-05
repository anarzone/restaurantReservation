<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Hall;
use App\Reservation;
use App\Restaurant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class FrontController extends Controller
{
    public function index(){
        $data = Restaurant::select('id','name')->whereNull('deleted_at')->where('status', '=', '1')->get();
        return view('front.form', ['restaurants' => $data]);
    }

    public function checkTableAvailability(Request $request){
        $request->note ?? $request->merge(['note' => '']);
        $request->lastname ?? $request->merge(['lastname' => '']);

        $rules =[
            'firstname'         => 'required|string|min:2',
            'lastname'          => 'string|min:2',
            'phone'             => 'required|string',
            'restaurant_id'     => 'required|alpha_num',
            'hall_id'           => 'required|alpha_num',
            'people'            => 'required|alpha_num',
            'reservation_date'  => 'required|date',
            'note'              => 'string'
        ];

        $messages = [
            'required' => 'Bu xananı doldurmaq vacibdir'
        ];

        Validator::make($request->all(), $rules, $messages);

        $phone = preg_replace('/\D/', '', trim($request->phone));

        $customer = Customer::where('phone', $phone)->first();
        if($customer){
            $customer->update(['status', Customer::STATUS_REGULAR]);
            $customer_id = $customer->id;
        }else{
            $customer_id = Customer::create([
                'firstname' => $request->firstname,
                'lastname'  => $request->lastname,
                'phone'     => $phone,
                'status'    => Customer::STATUS_NEW,
            ])->id;
        }
        $data = Reservation::create([
            'res_firstname' => $request->firstname,
            'res_lastname' => $request->lastname,
            'res_phone' => $phone,
            'res_people' => $request->people,
            'res_restaurant_id' => $request->restaurant_id,
            'res_hall_id' => $request->hall_id,
            'datetime' => $request->reservation_date,
            'status' => Reservation::STATUS_PENDING,
            'customer_id' => $customer_id,
            'note'  => $request->note
        ]);


        return response()->json(['message'=>'success', 'data' => $data], Response::HTTP_CREATED);
    }

    public function sendForm(Request $request){
        $request->note ?? $request->merge(['note' => '']);
        $request->lastname ?? $request->merge(['lastname' => '']);

        $rules =[
            'firstname'         => 'required|string|min:2',
            'lastname'          => 'string|min:2',
            'country_code'      => 'required|string',
            'phone'             => 'required|string',
            'restaurant_id'     => 'required|alpha_num',
            'hall_id'           => 'required|alpha_num',
            'people'            => 'required|alpha_num',
            'reservation_date'  => 'required|date',
            'note'              => 'present'
        ];

        $messages = [
            'required' => 'Bu xananı doldurmaq vacibdir'
        ];

        Validator::make($request->all(), $rules, $messages);

        $country_code = preg_replace('/\+/', '', trim($request->country_code));
        $phone = preg_replace('/\D/', '', trim($request->phone));
        $phone = preg_replace('/^0/', '', $phone);

        $phone = $country_code.$phone;

        $customer = Customer::where('phone', $phone)->first();
        if($customer){
            $customer->update(['status', Customer::STATUS_REGULAR]);
            $customer_id = $customer->id;
        }else{
            $customer_id = Customer::create([
                'firstname' => $request->firstname,
                'lastname'  => $request->lastname,
                'phone'     => $phone,
                'status'    => Customer::STATUS_NEW,
            ])->id;
        }
        $date =$request->reservation_date.' '.preg_replace('/\s+/', '', $request->reservation_time);

        $data = Reservation::create([
            'res_firstname' => $request->firstname,
            'res_lastname' => $request->lastname,
            'res_phone' => $phone,
            'res_people' => $request->people,
            'res_restaurant_id' => $request->restaurant_id,
            'res_hall_id' => $request->hall_id,
            'datetime' => Carbon::createFromFormat('d-m-Y H:i', $date)->toDateTimeString(),
            'status' => Reservation::STATUS_PENDING,
            'customer_id' => $customer_id,
            'note'  => $request->note
        ]);


        return response()->json(['message'=>'success', 'data' => $data], Response::HTTP_CREATED);
    }

    public function filterPhone($phone){

    }

    public function getHallsByRestId($rest_id){
        $halls = Hall::select('id','name')->where('restaurant_id','=', $rest_id)->get();
        return response()->json(['message'=> 'success', 'data' => $halls], Response::HTTP_OK);
    }
}
