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
        return view('admin.pages.reservations');
    }

}
