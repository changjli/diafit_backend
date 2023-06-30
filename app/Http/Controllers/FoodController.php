<?php

namespace App\Http\Controllers;

use App\Models\Food;
use App\Http\Requests\StoreFoodRequest;
use App\Http\Requests\UpdateFoodRequest;
use Faker\Provider\bn_BD\Utils;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Http;

class FoodController extends Controller
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.food.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFoodRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFoodRequest $request)
    {
        $rules = [
            'name' => 'required|string',
            'serving_size' => 'required|between:0,9999',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'date' => 'required|date',
            'image' => 'required|string',
        ];

        $request->validate($rules);

        // get food data 
        $promise = Http::withHeaders([
            "X-Api-Key" => "+vqlyiycXk3ACPLF1J/+3Q==wbWiMSw2bY84EaBf"
        ])->get("http://api.api-ninjas.com/v1/nutrition", [
            'query' => $request->name,
            'serving' => $request->serving_size,
        ]);

        Food::create([
            'id' => IdGenerator::generate([
                'table' => 'food',
                'field' => 'id',
                'length' => 6,
                'prefix' => 'FO'
            ]),
            'name' => $request->name,
            'serving_size' => $request->serving_size,
            'calories' => $promise[0]["calories"],
            'proteins' => $promise[0]["protein_g"],
            'fats' => $promise[0]["fat_total_g"],
            'carbs' => $promise[0]["carbohydrates_total_g"],
            'price' => $request->price,
            'stock' => $request->stock,
            'date' => $request->date,
            'image' => $request->image,
        ]);

        return redirect('/')->with('success', 'new food has been added');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function show(Food $food)
    {
        //
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
     * @param  \App\Http\Requests\UpdateFoodRequest  $request
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFoodRequest $request, Food $food)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Food  $food
     * @return \Illuminate\Http\Response
     */
    public function destroy(Food $food)
    {
        Food::where('id', $food->id)->delete();

        return redirect('/')->with('success', 'food has been deleted');
    }
}
