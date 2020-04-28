<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Reservation;
use App\Restaurant;
use App\Role;
use App\Table as Hall_Table;
use App\User;
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

    public function createUsers(){
        return view('admin.pages.users.create');
    }

    public function getUsers(){
        $users = User::whereNull('deleted_at')->get();
        return view('admin.pages.users.index', ['users' => $users]);
    }

    public function getRoles(){
        $roles = Role::all();
        return response()->json([
            'message' => 'Success',
            'data'    => $roles
        ]);
    }

    public function updateUser(Request $request){
        $request->validate([
            'user_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'name'    => 'required|string',
            'email'   => 'required|email'
        ]);
        $user = User::find($request->user_id);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email
        ]);

        $user->roles()->sync([$request->role_id]);

        return response()->json([
            'message' => 'success',
            'data'    => $user
        ]);
    }

    public function storeUser(Request $request){

        $request->validate([
            'name'       => 'required|string|min:2',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role_id'    => 'required|numeric'
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
        ]);

        $user->roles()->attach($request->role_id);
        return redirect()->route('admin.users.index');
    }
}
