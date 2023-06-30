<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Haruncpi\LaravelIdGenerator\IdGenerator;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        // sebenernya sama kaya yang pertama sih, cuman handle errornya lebih bagus 

        // custom validation, another form of validation 
        // validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users',
            'password' => 'required'
        ]);

        // kalo validasi gagal 
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()], 422);
        }

        // $validatedData = $validator->validated();

        // return response()->json($validatedData);

        // masukkin database
        try {
            $user = User::create([
                'id' => IdGenerator::generate([
                    'table' => 'users',
                    'field' => 'id',
                    'length' => 6,
                    'prefix' => 'US'
                ]),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
                'nutritionGoal' => 2000,
                'exerciseGoal' => 1000,
                'glucoseGoal' => 120,
            ]);

            // buat token 
            $token = $user->createToken('api_token')->plainTextToken;
            // ini masuk ke table sendiri bukan ke column api_token di table user 

            return response()->json([
                'success' => true,
                'data' => $user,
                'token' => $token,
            ], 201);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 409);
        }

        // // kalo berhasil 
        // if ($user) {
        //     return response()->json([
        //         'success' => true,
        //         'data' => $user,
        //     ], 201);
        // }

        // // kalo gagal 
        // return response()->json([
        //     'success' => false,
        // ], 409);

        // jadi kalo error code itu ditaruh di paling akhir diluar associative array 

        // ini yang pertama 
        // $rules = [
        //     'email' => 'required|unique:users|email:dns',
        //     'password' => 'required|same:passwordConfirmation',
        // ];

        // try {
        //     $validatedData = $request->validate($rules);

        //     return response()->json($validatedData);

        //     User::create($validatedData);
        // } catch (Exception $e) {
        //     return response()->json(['message' => $e->getMessage(), 'code' => 422]);
        // };
    }
}
