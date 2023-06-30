<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Nutrition;
use Illuminate\Http\Request;
use App\Models\NutritionReport;

use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class NutritionApiController extends Controller
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
            $nutritions = Nutrition::where('user_id', $user->id)->get();

            if ($nutritions->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no nutrition records']);
            }

            return response()->json(['success' => true, 'data' => $nutritions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        $user = auth()->user();

        try {
            $nutritions = Nutrition::where('user_id', $user->id)->whereDate('date', '=', date($request->date))->get();

            if ($nutritions->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no nutrition records']);
            }

            return response()->json(['success' => true, 'data' => $nutritions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function interval(Request $request)
    {
        $user = auth()->user();

        try {
            // $nutritions = Nutrition::where('user_id', $user->id)
            //     ->select(DB::raw('date(created_at) as date, count(date(created_at)) as nutrition_count, sum(calories) as calories_consumed'))
            //     ->groupBy(DB::raw('date(created_at)'))
            //     ->get();

            // $nutritions = Nutrition::where('user_id', $user->id)
            //     ->whereBetween('date', [$request->start_date, $request->end_date])
            //     ->get();

            $nutritions = Nutrition::where('user_id', $user->id)
                ->select(DB::raw('date(date) as date, sum(calories) as calories_consumed'))
                ->groupBy(DB::raw('date(date)'))
                ->whereBetween('date', [date($request->start_date), date($request->end_date)])
                ->get();

            if ($nutritions->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no nutrition records']);
            }

            return response()->json(['success' => true, 'data' => $nutritions]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function summary(Request $request)
    {
        $user = auth()->user();

        try {
            $nutritions = Nutrition::where('user_id', $user->id)
                ->select(DB::raw('date(date) as date, sum(calories) as calories_consumed'))
                ->groupBy(DB::raw('date(date)'))
                ->whereDate('date', '=', date($request->start_date))
                // karena udah pake datetime harus spesifik sampe time jadinya pake date aja 
                // ->whereBetween('date', [date($request->start_date), date($request->end_date)])
                // ->where('date', date($request->start_date))
                ->get();

            if ($nutritions->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no nutrition records']);
            }

            return response()->json(['success' => true, 'data' => $nutritions]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = auth()->user();
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'calories' => 'required|between:0,9999',
                'serving_size_g' => 'required|between:0,9999',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $nutrition = Nutrition::create([
                'id' => IdGenerator::generate([
                    'table' => 'nutrition',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'NU'
                ]),
                'user_id' => $user->id,
                'name' => $request->name,
                'calories' => $request->calories,
                'serving_size_g' => $request->serving_size_g,
                'date' => $request->date,
            ]);

            return response()->json(['success' => true, 'data' => $nutrition]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Nutrition  $nutrition
     * @return \Illuminate\Http\Response
     */
    public function show(Nutrition $nutrition)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Nutrition  $nutrition
     * @return \Illuminate\Http\Response
     */
    public function edit(Nutrition $nutrition)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Nutrition  $nutrition
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Nutrition $nutrition)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Nutrition  $nutrition
     * @return \Illuminate\Http\Response
     */
    public function destroy(Nutrition $nutrition)
    {
        try {
            Nutrition::where('id', $nutrition->id)->delete();

            return response()->json(['success' => true, 'message' => 'nutrition record has been deleted']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
