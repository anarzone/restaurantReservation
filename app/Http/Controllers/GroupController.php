<?php

namespace App\Http\Controllers;

use App\Group;
use App\Restaurant;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class GroupController extends Controller
{
  /**
  * __construct
  */
  public function __construct()
  {
    $this->middleware('role:super-admin');
  }

  /**
  * index
  */
  public function index()
  {
    $groups = Group::whereNull('deleted_at')->get();
    return view('admin.pages.groups.index', ['groups' => $groups]);
  }

  /**
  * create
  */
  public function create(){
    $restaurants = Restaurant::where('status', Restaurant::AVAILABLE)->get();
    return view('admin.pages.groups.create', ['restaurants' => $restaurants]);
  }

  /**
  * store
  */
  public function store(Request $request){
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

    $request->session()->flash('message-success', "Qrup yaradıldı");

    $group_created->restaurants()->attach($request->restaurants);

    return redirect()->route('manage.groups.index');
  }

  /**
  * edit
  */
  public function edit($group_id){
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

  /**
  * update
  */
  public function update(Request $request, Group $group){
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

    $request->session()->flash('message-success', "Qrup yeniləndi");

    return redirect()->route('manage.groups.index');
  }

  /**
  * destroy
  */
  public function destroy(Group $group, Request $request){
    $group->restaurants()->detach();
    $group->users()->detach();
    
    $group->delete();

    $request->session()->flash('message-danger', "Qrup silindi");

    return response()->json([
      'message' => 'Success',
    ], Response::HTTP_NO_CONTENT);
  }
}
