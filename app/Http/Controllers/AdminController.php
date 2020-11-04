<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\ProfileUpdateRequest;
use App\Restaurant;
use App\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;

class AdminController extends Controller
{

  /**
  * Displaying dashboard page
  */
  public function dashboard(){

    $restaurants = Restaurant::whereNull('deleted_at')
    ->whereIn('id', Auth::user()->inGroupRestaurants())
    ->where('status', '=', Restaurant::AVAILABLE)
    ->with('halls')->get();

    return view('admin.pages.dashboard', ['restaurants' => $restaurants]);
  }

  /**
  * getRoles
  */
  public function getRoles(){
    $roles = Role::all();
    return response()->json([
      'message' => 'Success',
      'data'    => $roles
    ]);
  }

  /**
  * getRolesAndGroups
  */
  public function getRolesAndGroups(){
    $roles = Role::all();
    $groups = Group::whereNull('deleted_at')->where('status', 1)->get();

    return response()->json([
      'message' => 'Success',
      'data'    => ['groups' => $groups, 'roles' => $roles]
    ]);
  }

  /**
  * showRoles
  */
  public function showRoles(){
    $roles = Role::all();
    return view('admin.pages.roles.index', ['roles' => $roles]);
  }

  /**
  * createRoles
  */
  public function createRoles(){
    $permissions = Permission::all();
    return view('admin.pages.roles.create', ['permissions' => $permissions]);
  }

  /**
  * showProfile
  */
  public function showProfile(){
    return view('admin.pages.users.profile', ['userdata' => Auth::user()]);
  }

  /**
  * updateProfile
  */
  public function updateProfile(ProfileUpdateRequest $request){
    $user = Auth::user();

    $request->validated();

    $user->update([
      'name' => $request->name,
      'email' => $request->email,
    ]);

    return redirect()->back()->with('msg', 'Məlumatlarınız uğurla yeniləndi');

  }

  /**
  * getForm
  */
  public function getForm(){
    $data = Restaurant::select('id','name')->whereNull('deleted_at')->where('status', '=', '1')->get();
    return view('admin.pages.reservation_form', ['restaurants' => $data]);
  }

}
