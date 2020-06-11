<?php

namespace App\Http\Controllers;

use App\Hall;
use App\Plan;
use App\PlanTable;
use App\Restaurant;
use App\Table;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class PlanController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:super-admin');
    }

    public function upload_image(){
        $restaurants = Restaurant::where('status', Restaurant::AVAILABLE)->get();
        $halls = Hall::all();
        return view('admin.pages.plans.image_upload', ['restaurants' => $restaurants, 'halls' => $halls]);
    }

    public function upload(Request $request){
        $rules = [
            'plan_image' => 'required|image|mimes:jpeg,png,jpg|max:5000',
            'hall_id'    => 'required|numeric'
        ];

        $messages = [
            'plan_image.required' => "Şəkil yüklənməsi vacibdir",
            'plan_image.mimes'    => "Ancaq jpg, jpeg və png formatları qəbul olunur",
            'plan_image.max'      => "5 mb-dan böyük şəkil yükləyə bilməzsiniz",
            'hall_id.required'    => "Zal boş ola bilməz"
        ];

        Validator::make($request->all(), $rules, $messages);

        if($request->hasFile('plan_image')){
            $image = $request->file('plan_image');
            $filename = time().'.'.$image->getClientOriginalExtension();
            $path = $image->storeAs('public/back/images', $filename);
            $old_image = Plan::where('hall_id', $request->hall_id)->first();

            $plan = Plan::updateOrCreate(
                ['hall_id' => $request->hall_id],
                [
                    'img_name'   => $filename,
                    'img_size'   => Storage::size($path),
                    'hall_id'    => $request->hall_id,
                ]
            );

            if($old_image){
                Storage::delete('public/back/images/'.$old_image->img_name);
            }

            return response()->json([
                'message' => 'Success',
                'data'    => $plan
            ]);
        }
    }

    public function create(Hall $hall){
        return view('admin.pages.plans.create', ['hall' => $hall]);
    }

    public function store(Request $request){
        $rules = [
            'plan_details' => 'required|array',
            'plan_id'      => 'required|integer',
            'hall_id'      => 'required|integer'
        ];

        $messages = [
            'required' => 'Bu sahə tələb olunur',
            'integer' => 'integer formatında olmalıdır',
        ];

        Validator::make($request->all(), $rules, $messages);

        foreach ($request->plan_details as $detail){
            PlanTable::create([
                'plan_id'   => $request->plan_id,
                'hall_id'   => $request->hall_id,
                'table_id'  => $detail['table_id'],
                'coords'    => $detail['coords']
            ]);
        }

        return response()->json([
            'message' => 'success'
        ], Response::HTTP_CREATED);
    }

    public function edit(Plan $plan){
        $planData =\DB::select('SELECT * FROM plan_tables where plan_id = ?', [$plan->id]);
        $hall_tables = Table::where('hall_id', $plan->hall_id)->get();
        return view('admin.pages.plans.edit', ['plan' => $plan, 'planData' => $planData, 'hall_tables' => $hall_tables]);
    }

    public function update(Plan $plan, Request $request){
        $rules = [
            'plan_details' => 'required|array',
            'plan_id'      => 'required|integer',
        ];

        $messages = [
            'required' => 'Bu sahə tələb olunur',
            'integer' => 'integer formatında olmalıdır',
        ];

        Validator::make($request->all(), $rules, $messages);
        if($request->deletable_tables){
            $ids = array_values($request->deletable_tables);
            PlanTable::whereIn('table_id', $ids)->delete();
        }

        foreach ($request->plan_details as $detail) {
            PlanTable::updateOrCreate(
                ['table_id' => $detail['table_id']],
                [
                    'table_id' => $detail['table_id'],
                    'coords'   => $detail['coords'],
                    'hall_id'  => $detail['hall_id'],
                    'plan_id'  => $detail['plan_id'],
                ]
            );
        }
        return response()->json(['message' => 'success'], Response::HTTP_CREATED);
    }

    public function getPlansByHallId(Hall $hall){
        return response()->json(['plan' => $hall->plan]);
    }
}
