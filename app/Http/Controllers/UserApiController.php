<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = User::all();
        return response()->json($user);
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'string',
                'gender' => 'string',
                'age' => 'integer',
                'height' => 'integer',
                'weight' => 'integer',
                'address' => 'string',
                'image' => 'string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            // return response()->json(['user' => $request->all()]);

            User::where('id', $user->id)->update([
                'name' => $request->name,
                'gender' => $request->gender,
                'age' => $request->age,
                'height' => $request->height,
                'weight' => $request->weight,
                'address' => $request->address,
                'weightGoal' => $request->weightGoal,
                'nutritionGoal' => $request->nutritionGoal,
                'exerciseGoal' => $request->exerciseGoal,
                'glucoseGoal' => $request->glucoseGoal,
                'image' => $request->image,
            ]);

            $new = User::where('id', $user->id)->first();

            return response()->json(['success' => true, 'data' => $new]);
        } catch (Exception $e) {
            return response(['success' => false, 'message' => $e->getMessage(), $e->getCode()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        try {
            auth()->user()->tokens()->delete();

            User::where('id', $user->id)->delete();

            return response(['success' => true, 'message' => 'user has been deleted']);
        } catch (Exception $e) {
            return response(['success' => false, 'message' => 'user delete error']);
        }
    }
}
