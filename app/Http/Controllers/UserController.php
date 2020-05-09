<?php

namespace App\Http\Controllers;

use App\Group;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index(){
        $users = User::whereNull('deleted_at')->with('roles')->with('groups')->get();
        return view('admin.pages.users.index', ['users' => $users]);
    }

    public function create(){
        $groups = Group::where('status', 1)->get();
        return view('admin.pages.users.create', ['groups' => $groups]);
    }

    public function store(Request $request){
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
            'password'  => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role_id);
        $user->groups()->attach($request->group_id);
        return redirect()->route('admin.users.index');
    }

    public function update(Request $request){
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

    public function destroy(User $user){
        $affected_user = $user->delete();

        return response()->json([
           'message' => 'İstifadəçi silindi',
            'data'   => $affected_user
        ]);
    }
}
