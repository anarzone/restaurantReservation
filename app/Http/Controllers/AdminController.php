<?php

namespace App\Http\Controllers;

use App\Group;
use App\Hall;
use App\Reservation;
use App\Restaurant;
use App\Role;
use App\Table as Hall_Table;
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


    public function createUsers(){
        $groups = Group::where('status', 1)->get();
        return view('admin.pages.users.create', ['groups' => $groups]);
    }

    public function getUsers(){
        $users = User::whereNull('deleted_at')->with('roles')->with('groups')->get();
        return view('admin.pages.users.index', ['users' => $users]);
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
        $groups = Group::where('status', 1)->get();

        return response()->json([
            'message' => 'Success',
            'data'    => ['groups' => $groups, 'roles' => $roles]
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

        $user->groups()->sync([$request->group_id]);
        $user->syncRoles($request->role_id);

        return response()->json([
            'message' => 'success',
            'data'    => $user
        ]);
    }

    public function storeUser(Request $request){
        $rules = [
            'name'       => 'required|string|min:2',
            'email'      => 'required|email|unique:users',
            'password'   => 'required|min:6',
            'role_id'    => 'required|numeric',
            'group_id'   => 'required|numeric',
        ];

        $messages = [
            'email.required' => 'Email vacib sahədir',
            'name.required' => 'Ad vacib sahədir',
            'password.required' => 'Şifrə vacib sahədir',
            'email.unique' => 'Bu email artıq qeydiyyatdan keçib',
            'password.min:6' => 'Şifrə ən az 6 xarakter olmalıdır',
            'name.min:2' => 'Ad ən az 2 xarakter olmalıdır',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->validate();

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => $request->password,
        ]);

        $user->roles()->attach($request->role_id);
        $user->groups()->attach($request->group_id);
        return redirect()->route('admin.users.index');
    }



    public function showGroups(){
        $groups = Group::all();
        return view('admin.pages.groups.index', ['groups' => $groups]);
    }

    public function createGroup(){
        $restaurants = Restaurant::where('status', Restaurant::AVAILABLE)->get();
        return view('admin.pages.groups.create', ['restaurants' => $restaurants]);
    }

    public function storeGroup(Request $request){
        $rules = [
            'group_name' => 'required',
        ];

        $messages = [
            'required' => 'Qrup adını daxil etməmisiniz',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->validate();

        $group_created = Group::create([
            'group_name' => $request->group_name
        ]);



        $group_created->restaurants()->attach($request->restaurants);

        return back();
    }

    public function editGroup($group_id){
        $group = Group::where('id', $group_id)->with('restaurants')->first();
        $group_rest_ids = [];
        foreach ($group->restaurants as $rest){
            $group_rest_ids[] = $rest->id;
        }
        $out_group_restaurants = Restaurant::where('status', 1)->whereNotIn('id', $group_rest_ids)->get();

        return view('admin.pages.groups.edit', [
            'group' => $group,
            'out_group_restaurants' => $out_group_restaurants
        ]);
    }

    public function updateGroup(Request $request, Group $group){
        $rules = [
            'group_name' => 'required',
        ];

        $messages = [
            'required' => 'Qrup adını daxil etməmisiniz',
        ];

        $validator = Validator::make($request->all(), $rules, $messages);
        $validator->validate();

        $group->update(['group_name' => $request->group_name]);

        $group->restaurants()->sync($request->restaurants);
        return redirect()->route('admin.groups.index');
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

        if($request->has('password') && Hash::check($request->input('password'), $user->password)){
            $rules['password'] = 'min:6';
            $rules['new_password'] = 'required|between:6,255';
            $rules['new_password_confirmation'] = 'required|same:new_password|min:6';
        }
        $messages = [
            'required'  => 'Bütün sahələri doldurmağınız vacibdir',
            'email'     => 'Doğru email ünvanını daxil etməmisiniz',
            'unique:users,email'    => 'Bu emaillə istifadəçi mövcuddur',
            'new_password_confirmation.same' => 'Şifrənin təsdiqi yalnışdır',
            'between' => 'Şifrə ən az 6 xarakterli olmalıdır'
        ];

        Validator::make($request->all(), $rules, $messages)->validate();

        $values = [
            'name' => $request->name,
            'email' => $request->email,
        ];
        if($request->has('new_password')){
            $values['password'] = Hash::make($request->new_password);
        }

        $user->update($values);

        return redirect()->back()->with('msg', 'Məlumatlarınız uğurla yeniləndi');

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
