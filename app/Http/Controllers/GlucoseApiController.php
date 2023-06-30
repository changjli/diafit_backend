<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\Glucose;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class GlucoseApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function report(Request $request)
    {
        $user = auth()->user();
        try {
            $glucoses = Glucose::where('user_id', $user->id)->whereDate('date', '=', date($request->date))->get();

            if ($glucoses->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no glucose records']);
            }

            return response()->json(['success' => true, 'data' => $glucoses]);
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
                'sugar_level' => 'required|between:0,9999',
                'date' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $glucose = Glucose::create([
                'id' => IdGenerator::generate([
                    'table' => 'glucoses',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'GL'
                ]),
                'user_id' => $user->id,
                'sugar_level' => $request->sugar_level,
                'date' => $request->date,
            ]);

            return response()->json(['success' => true, 'data' => $glucose]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'error' => $e->getMessage(), $e->getCode()]);
        }
    }

    public function summary(Request $request)
    {
        $user = auth()->user();

        try {
            $glucoses = Glucose::where('user_id', $user->id)
                ->select(DB::raw('date(date) as date, avg(sugar_level) as sugar_level'))
                ->groupBy(DB::raw('date(date)'))
                ->whereDate('date', '=', date($request->date))
                ->get();

            if ($glucoses->isEmpty()) {
                return response()->json(['success' => false, 'message' => 'there are no glucose records']);
            }

            return response()->json(['success' => true, 'data' => $glucoses]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Glucose  $glucose
     * @return \Illuminate\Http\Response
     */
    public function show(Glucose $glucose)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Glucose  $glucose
     * @return \Illuminate\Http\Response
     */
    public function edit(Glucose $glucose)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Glucose  $glucose
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Glucose $glucose)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Glucose  $glucose
     * @return \Illuminate\Http\Response
     */
    public function destroy(Glucose $glucose)
    {
        try {
            Glucose::where('id', $glucose->id)->delete();

            return response()->json(['success' => true, 'message' => 'glucose record has been deleted']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
