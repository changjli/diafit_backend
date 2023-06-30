<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ExerciseApiController extends Controller
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
            $exercises = Exercise::where('user_id', $user->id)->get();

            if ($exercises->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no exercise records']);
            }

            return response()->json(['success' => true, 'data' => $exercises]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function report(Request $request)
    {
        $user = auth()->user();

        try {
            $exercises = Exercise::where('user_id', $user->id)->whereDate('date', '=', date($request->date))->get();

            if ($exercises->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no exercise records']);
            }

            return response()->json(['success' => true, 'data' => $exercises]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function interval(Request $request)
    {
        $user = auth()->user();

        try {
            // $exercises = Exercise::where('user_id', $user->id)
            //     ->select(DB::raw('date(date) as date, count(date(date)) as nutrition_count, sum(total_calories) as calories_burned'))
            //     ->groupBy(DB::raw('date(date)'))
            //     ->get();

            $exercises = Exercise::where('user_id', $user->id)
                ->select(DB::raw('date(date) as date, sum(total_calories) as calories_burned'))
                ->groupBy(DB::raw('date(date)'))
                ->whereBetween('date', [date($request->start_date), date($request->end_date)])
                ->get();

            if ($exercises->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no exercise records']);
            }

            return response()->json(['success' => true, 'data' => $exercises]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function summary(Request $request)
    {
        $user = auth()->user();

        try {
            $exercises = Exercise::where('user_id', $user->id)
                ->select(DB::raw('date(date) as date, sum(total_calories) as calories_burned'))
                ->groupBy(DB::raw('date(date)'))
                ->whereDate('date', '=', date($request->start_date))
                ->get();

            if ($exercises->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no exercise records']);
            }

            return response()->json(['success' => true, 'data' => $exercises]);
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
                'duration_minutes' => 'required|between:0,9999',
                'calories_per_hour' => 'required|between:0,9999',
                'total_calories' => 'required|between:0,9999',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $exercise = Exercise::create([
                'id' => IdGenerator::generate([
                    'table' => 'exercises',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'EX'
                ]),
                'user_id' => $user->id,
                'name' => $request->name,
                'duration_minutes' => $request->duration_minutes,
                'calories_per_hour' => $request->calories_per_hour,
                'total_calories' => $request->total_calories,
                'date' => $request->date,
            ]);

            return response()->json(['success' => true, 'data' => $exercise]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function show(Exercise $exercise)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function edit(Exercise $exercise)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Exercise $exercise)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Exercise  $exercise
     * @return \Illuminate\Http\Response
     */
    public function destroy(Exercise $exercise)
    {
        try {
            Exercise::where('id', $exercise->id)->delete();

            return response()->json(['success' => true, 'message' => 'excercise record has been deleted']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
