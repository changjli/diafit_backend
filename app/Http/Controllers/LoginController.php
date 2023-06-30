<?php

namespace App\Http\Controllers;

use App\Models\User;
use Hamcrest\Core\IsEqual;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Sanctum\HasApiTokens;

class LoginController extends Controller
{
    // user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        // $validatedData = $validator->validated();

        // return response()->json($validatedData);

        // auth 
        if (!Auth::attempt($request->only('email', 'password'))) {
            return response()->json(['success' => false, 'message' => 'email or password error'], 401);
        }

        // query user
        $user = User::where('email', $request->email)->first();

        // return response()->json($user);

        // create token 
        $token = $user->createToken('auth_token')->plainTextToken;

        // auth token gaada ? 

        return response()->json([
            'success' => true,
            'id' => $user->id,
            'data' => $user,
            'token' => $token,
            'token_type' => 'bearer',
        ]);
    }

    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'success']);
    }

    // admin
    public function index()
    {
        return view('admin.login');
    }

    public function adminLogin(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|min:6|max:255'
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user != null) {
            if ($user->role == "admin") {
                if (Auth::attempt($credentials)) {
                    $request->session()->regenerate();

                    return redirect()->intended('/');
                }
            }
        }
        return back()->with('error', 'login failed');
    }
}
