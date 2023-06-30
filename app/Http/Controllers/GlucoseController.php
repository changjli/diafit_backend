<?php

namespace App\Http\Controllers;

use App\Models\Glucose;
use App\Http\Requests\StoreGlucoseRequest;
use App\Http\Requests\UpdateGlucoseRequest;

class GlucoseController extends Controller
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
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreGlucoseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreGlucoseRequest $request)
    {
        //
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
     * @param  \App\Http\Requests\UpdateGlucoseRequest  $request
     * @param  \App\Models\Glucose  $glucose
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateGlucoseRequest $request, Glucose $glucose)
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
        //
    }
}
