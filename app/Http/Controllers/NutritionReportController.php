<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\NutritionReport;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use App\Http\Requests\StoreNutritionReportRequest;
use App\Http\Requests\UpdateNutritionReportRequest;

class NutritionReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = auth()->user();
        try {
            // this is just another way to do it 
            // $nutritionReports = NutritionReport::whereHas('user', function ($query) use ($user) {
            //     $query->where('id', $user->id)->get();
            // });

            $nutritionReports = NutritionReport::where('user_id', $user->id)->get();

            if ($nutritionReports->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no nutrition report records']);
            }

            return response()->json(['success' => true, 'data' => $nutritionReports]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreNutritionReportRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreNutritionReportRequest $request)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'date' => 'required|date',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $nutrition = NutritionReport::create([
                'id' => IdGenerator::generate([
                    'table' => 'nutrition_reports',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'NR'
                ]),
                'user_id' => $user->id,
                'date' => Carbon::today(),
            ]);

            return response()->json(['success' => true, 'data' => $nutrition]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\NutritionReport  $nutritionReport
     * @return \Illuminate\Http\Response
     */
    public function show(NutritionReport $nutritionReport)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\NutritionReport  $nutritionReport
     * @return \Illuminate\Http\Response
     */
    public function edit(NutritionReport $nutritionReport)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateNutritionReportRequest  $request
     * @param  \App\Models\NutritionReport  $nutritionReport
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateNutritionReportRequest $request, NutritionReport $nutritionReport)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\NutritionReport  $nutritionReport
     * @return \Illuminate\Http\Response
     */
    public function destroy(NutritionReport $nutritionReport)
    {
        //
    }
}
