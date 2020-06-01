<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CustomerController extends Controller
{
    public function index(){

        return view('admin.pages.customers.index', [
           'customers' => Customer::latest()->paginate(10),
        ]);
    }
    public function getReservationsByCustomer(Customer $customer){
        $reservation_info = Reservation::leftJoin('customers  as c', 'reservations.customer_id', '=', 'c.id')
                                       ->leftjoin('restaurants as r', 'reservations.res_restaurant_id', '=', 'r.id')
                                       ->leftJoin('halls as h', 'reservations.res_hall_id', '=', 'h.id')
                                       ->leftJoin('tables as t', 'reservations.table_id', '=', 't.id')
                                       ->where('c.id', $customer->id)
                                       ->select('reservations.*', 'r.name as restaurant_name', 'c.phone', 'h.name as hall_name', 't.table_number')
                                       ->get()
        ;
        return response()->json([
            'data' => $reservation_info,
        ], Response::HTTP_OK);
    }
}
