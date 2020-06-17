<?php

namespace App\Http\Controllers;

use App\Group;
use App\Http\Requests\StuffStoreRequest;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function index(){
        $users = User::whereNull('deleted_at')->with('roles')->with('groups')->get();
        return view('admin.pages.users.index', ['users' => $users]);
    }

    public function create(){
        $groups = Group::where('status', 1)->get();
        return view('admin.pages.users.create', ['groups' => $groups]);
    }

    public function store(StuffStoreRequest $request){
        $request->validated();

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
        ]);

        $user->roles()->attach($request->role_id);
        $user->groups()->attach($request->group_id);

        $request->session()->flash('message-success', "Yeni istifadəçi yaradıldı");

        return redirect()->route('admin.users.index');
    }

    public function update(StuffStoreRequest $request){
        $request->validated();

        $user = User::find($request->user_id);

        $user->update([
            'name'  => $request->name,
            'email' => $request->email
        ]);

        $user->groups()->sync([$request->group_id]);

        if ($request->user_id != auth()->user()->id){
            $user->syncRoles($request->role_id);
        }

        $request->session()->flash('message-success', "Uğurla yeniləndi");

        return response()->json([
            'message' => 'success',
            'data'    => $user
        ]);
    }

    public function destroy(User $user, Request $request){
        $affected_user = $user->delete();

        $request->session()->flash('message-delete', 'İstifadəçi silindi');

        return response()->json([
           'message' => 'İstifadəçi silindi',
            'data'   => $affected_user
        ]);
    }
}
