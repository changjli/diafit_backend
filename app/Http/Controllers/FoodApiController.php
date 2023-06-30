<?php

namespace App\Http\Controllers;

use Exception;
use Carbon\Carbon;
use App\Models\Food;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\Framework\isEmpty;

use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class FoodApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            // $foods = Food::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->get();

            // kalo pindah minggu otomatis berubah
            $foods = Food::whereBetween('date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                ->select(DB::raw('date as date, count(date) as food_count'))
                ->groupBy(DB::raw('date'))
                ->get();

            return response()->json(['success' => true, 'data' => $foods->sortBy('date')]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function menu(Request $request)
    {
        try {
            $foods = Food::where('date', $request->date)->get();

            return response()->json(['success' => true, 'data' => $foods]);
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
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'serving_size' => 'required|between:0,9999',
                'calories' => 'required|between:0,9999',
                'proteins' => 'required|between:0,9999',
                'fats' => 'required|between:0,9999',
                'carbs' => 'required|between:0,9999',
                'price' => 'required|integer',
                'date' => 'required|date',
                'image' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            $food = Food::create([
                'id' => IdGenerator::generate([
                    'table' => 'food',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'FO'
                ]),
                'name' => $request->name,
                'serving_size' => $request->serving_size,
                'calories' => $request->calories,
                'proteins' => $request->proteins,
                'fats' => $request->fats,
                'carbs' => $request->carbs,
                'price' => $request->price,
                'date' => $request->date,
                'image' => $request->image,
            ]);

            return response()->json(['success' => true, 'data' => $food]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        return response()->json(['success' => true, 'data' => $food]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function edit(Food $food)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Food $food)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'serving_size' => 'required|between:0,9999',
                'calories' => 'required|between:0,9999',
                'proteins' => 'required|between:0,9999',
                'fats' => 'required|between:0,9999',
                'carbs' => 'required|between:0,9999',
                'price' => 'required|integer',
                'date' => 'required|date',
                'image' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['succcess' => false, 'message' => $validator->errors()]);
            }

            Food::where('id', $food->id)->update([
                'name' => $request->name,
                'serving_size_g' => $request->serving_size,
                'calories' => $request->calories,
                'proteins' => $request->proteins,
                'fats' => $request->fats,
                'carbs' => $request->carbs,
                'price' => $request->price,
                'date' => $request->date,
                'image' => $request->image,
            ]);

            $new = Food::where('id', $food->id)->first();

            return response()->json(['success' => true, 'data' => $new]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        try {
            Food::where('id', $food->id)->delete();

            return response()->json(['success' => true, 'message' => 'food has been deleted']);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
