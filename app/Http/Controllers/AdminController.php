<?php

namespace App\Http\Controllers;

use App\Group;
use App\Hall;
use App\Reservation;
use App\Restaurant;
use App\Role;
use App\Table as Hall_Table;
use App\Table as HallTable;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use phpDocumentor\Reflection\Types\Null_;
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

    public function getRoles(){
        $roles = Role::all();
        return response()->json([
            'message' => 'Success',
            'data'    => $roles
        ]);
    }

    public function getRolesAndGroups(){
        $roles = Role::all();
        $groups = Group::whereNull('deleted_at')->where('status', 1)->get();

        return response()->json([
            'message' => 'Success',
            'data'    => ['groups' => $groups, 'roles' => $roles]
        ]);
    }

    public function showRoles(){
        $roles = Role::all();
        return view('admin.pages.roles.index', ['roles' => $roles]);
    }

    public function createRoles(){
        $permissions = Permission::all();
        return view('admin.pages.roles.create', ['permissions' => $permissions]);
    }

    public function showProfile(){
        return view('admin.pages.users.profile', ['userdata' => Auth::user()]);
    }

    public function updateProfile(Request $request){
        $user = Auth::user();
        $rules = [
            'name'  => 'required|string',
            'email' => 'required|email|unique:users,email,'.Auth::user()->id,
        ];
        $messages = [
            'name.required'  => 'Adınızı daxil edin',
            'email.required'  => 'İşlək email daxil edin',
            'email'     => 'Doğru email ünvanını daxil edin',
            'unique:users,email'    => 'Bu emaillə istifadəçi mövcuddur',
        ];

        if($request->has('password') && Hash::check($request->input('password'), $user->password)){
            $rules['new_password'] = 'required|between:6,255';
            $rules['new_password_confirmation'] = 'required|same:new_password|min:6';

            $messages['new_password.required'] = 'Yeni şifrəni daxil edin';
            $messages['new_password_confirmation.required'] = 'Yeni şifrənin təkrarını daxil edin';
            $messages['new_password_confirmation.same'] = 'Şifrənin təsdiqi yalnışdır';
            $messages['between'] = 'Şifrə ən az 6 xarakterli olmalıdır';
        }


        Validator::make($request->all(), $rules, $messages)->validate();

        $values = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if($request->new_password){
            $values['password'] = Hash::make($request->new_password);
        }

        $user->update($values);

        return redirect()->back()->with('msg', 'Məlumatlarınız uğurla yeniləndi');

    }

    public function getForm(){
        $data = Restaurant::select('id','name')->whereNull('deleted_at')->where('status', '=', '1')->get();
        return view('admin.pages.reservation_form', ['restaurants' => $data]);
    }

    // will return to this function when role editing activated
//    public function editRoles($id){
//        $rol_permission_ids = [];
//        $role = Role::where('id', $id)->with('permissions')->first();
//        foreach ($role->permissions as $rol){
//            $rol_permission_ids[] = $rol->id;
//        }
//        $unassosiated_permissions = Permission::whereNotIn('id', $rol_permission_ids)->get();
//        return view('admin.pages.roles.edit', ['role' => $role, 'unassosiated_permissions' => $unassosiated_permissions]);
//    }
}
