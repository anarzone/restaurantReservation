<?php

namespace App\Http\Controllers;

use App\Customer;
use App\Reservation;
use Carbon\Carbon;
use Cassandra\Custom;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{

  /**
  * index
  */
  public function index(){
    return view('admin.pages.customers.index', [
      'customers' => Customer::latest()->paginate(10),
    ]);
  }

  /**
  * edit
  */
  public function edit(Customer $customer){
    return view('admin.pages.customers.edit', [
      'customer' => $customer
    ]);
  }

  /**
  * update
  */
  public function update(Request $request, Customer $customer){
    $request->lastname ?? $request->merge(['lastname' => '']);

    $rules = [
      'firstname' => 'required|string|min:2',
      'lastname'  => 'nullable|string',
      'phone'     => 'required',
      'birthdate' => 'nullable|date',
    ];


    $messages = [
      'firstname.required' => 'Ad yazmaq tələb olunur',
      'phone.required'     => 'Telefon yazılmayıb',
      'date'               => 'Tarix doğru formatda deyil',
    ];

    $validator = Validator::make($request->all(), $rules, $messages);


    if($validator->fails()) {
      return redirect()->back()->withErrors($validator->errors());
    }

    $request->merge([
      'birthdate' => Carbon::parse($request->birthdate)->toDateString(),
    ]);

    $customer->update($request->all());

    return redirect()->route('manage.customers.index')->with('message', 'Müştəri məlumatları yeniləndi');
  }

  /**
  * destroy
  */
  public function destroy(Request $request, Customer $customer){
    $customer->delete();
    $customer->reservations()->delete();

    $request->session()->flash('message-danger', 'İstifadəçi silindi');
  }

  /**
  * getReservationsByCustomer
  */
  public function getReservationsByCustomer(Customer $customer){
    $reservation_info = Reservation::leftJoin('customers  as c', 'reservations.customer_id', '=', 'c.id')
    ->leftjoin('restaurants as r', 'reservations.res_restaurant_id', '=', 'r.id')
    ->leftJoin('halls as h', 'reservations.res_hall_id', '=', 'h.id')
    ->leftJoin('tables as t', 'reservations.table_id', '=', 't.id')
    ->where('c.id', $customer->id)
    ->withTrashed()
    ->select('reservations.*', 'r.name as restaurant_name', 'c.phone', 'h.name as hall_name', 't.table_number')
    ->get()
    ;
    return response()->json([
      'data' => $reservation_info,
    ], Response::HTTP_OK);
  }

}
